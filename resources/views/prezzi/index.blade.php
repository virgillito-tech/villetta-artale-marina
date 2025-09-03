@extends('layouts.admin')

@section('title', 'Gestione Prezzi - Villetta Artale Marina')

@section('content')
<div class="container-fluid">
    <!-- Alert Container -->
    <div id="alertContainer"></div>

    <!-- Statistiche -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="bi bi-calendar-check display-4 text-primary mb-3"></i>
                    <h4 class="card-title" id="giorni-configurati">{{ $prezzi->count() }}</h4>
                    <p class="card-text">Giorni Configurati</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="bi bi-currency-euro display-4 text-success mb-3"></i>
                    <h4 class="card-title" id="prezzo-medio">€{{ number_format($stats['media'] ?? 0, 0) }}</h4>
                    <p class="card-text">Prezzo Medio</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="bi bi-graph-up display-4 text-warning mb-3"></i>
                    <h4 class="card-title" id="prezzo-massimo">€{{ number_format($stats['max'] ?? 0, 0) }}</h4>
                    <p class="card-text">Prezzo Massimo</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="bi bi-graph-down display-4 text-info mb-3"></i>
                    <h4 class="card-title" id="prezzo-minimo">€{{ number_format($stats['min'] ?? 0, 0) }}</h4>
                    <p class="card-text">Prezzo Minimo</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Azioni Rapide -->
    <div class="card mb-4">
        <div class="card-header">
            <h5><i class="bi bi-lightning me-2"></i>Azioni Rapide</h5>
        </div>
        <div class="card-body">
            <div class="row g-2">
                <div class="col-md-3">
                    <button class="btn btn-outline-primary w-100" onclick="setSeasonalPrices('estate')">
                        <i class="bi bi-sun me-1"></i>Prezzi Estivi
                    </button>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-outline-primary w-100" onclick="setSeasonalPrices('inverno')">
                        <i class="bi bi-snow me-1"></i>Prezzi Invernali
                    </button>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-outline-primary w-100" onclick="setWeekendPrices()">
                        <i class="bi bi-calendar-week me-1"></i>Prezzi Weekend
                    </button>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-outline-danger w-100" onclick="clearAllPrices()">
                        <i class="bi bi-trash me-1"></i>Cancella Tutto
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Calendario -->
    <div class="card">
        <div class="card-header">
            <h5><i class="bi bi-calendar me-2"></i>Calendario Prezzi</h5>
        </div>
        <div class="card-body">
            <div id="calendar" style="height: 600px;"></div>
        </div>
    </div>
</div>

<!-- Modal Gestione Prezzo -->
@include('prezzi.partials.price-modal')

<script>
// Variabili globali
let prezziData = @json($prezzi->keyBy('data')) || {};
const csrfToken = '{{ csrf_token() }}';
const storeUrl = '{{ route("prezzi.store") }}';
const updateUrl = '{{ route("prezzi.update", ":id") }}';
const deleteUrl = '{{ route("prezzi.destroy", ":id") }}';
let calendar = null;
let currentEditingDate = null;

// Inizializzazione quando la pagina è carica
document.addEventListener('DOMContentLoaded', function() {
    console.log('Inizializzazione sistema prezzi...');
    initializeCalendar();
    updateStatistics();
    setupEventListeners();
});

// Inizializza il calendario
function initializeCalendar() {
    const calendarEl = document.getElementById('calendar');
    
    if (!calendarEl) {
        console.error('Elemento calendario non trovato');
        return;
    }
    
    calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'it',
        height: 600,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,dayGridWeek'
        },
        selectable: true,
        selectMirror: true,
        dayMaxEvents: true,
        weekends: true,
        events: getCalendarEvents(),
        
        dateClick: function(info) {
            openPriceModal(info.dateStr);
        },
        
        eventClick: function(info) {
            info.jsEvent.preventDefault();
            openPriceModal(info.event.startStr, true);
        },
        
        select: function(info) {
    if (info.start && info.end) {
        const endDate = new Date(info.end.valueOf());
        endDate.setDate(endDate.getDate() - 1);
        openPriceModal(info.startStr, false, endDate.toISOString().substring(0, 10));
    }
}
    });

    calendar.render();
    console.log('Calendario inizializzato con successo');
}

// Ottieni gli eventi del calendario
function getCalendarEvents() {
    const events = [];

    Object.keys(prezziData).forEach(date => {
        const prezzo = prezziData[date];  // Dichiara prezzo una sola volta

        if (prezzo.is_closed) {
            events.push({
                title: 'Chiuso',
                start: date,
                allDay: true,
                backgroundColor: '#dc3545',
                borderColor: '#dc3545',
                textColor: 'white',
                extendedProps: {
                    prezzi: prezzo
                }
            });
            return; // Esci da qui, va bene così
        }

        const basePrice = parseFloat(prezzo.prezzo_1) || 0;

        let backgroundColor = '#28a745'; // Verde
        let textColor = 'white';

        if (basePrice > 150) {
            backgroundColor = '#dc3545'; // Rosso
            textColor = 'white';
        } else if (basePrice > 100) {
            backgroundColor = '#ffc107'; // Giallo
            textColor = 'black';
        }

        events.push({
            title: `€${basePrice.toFixed(0)}`,
            start: date,
            allDay: true,
            backgroundColor: backgroundColor,
            borderColor: backgroundColor,
            textColor: textColor,
            extendedProps: {
                prezzi: prezzo
            }
        });
    });

    return events;
}

// Apri modal per gestione prezzo
let currentEditingEndDate = null;
function openPriceModal(date, isEdit = false, endDate = null) {
    console.log('openPriceModal:', { date, endDate, isEdit });
    currentEditingDate = date;
    currentEditingEndDate = endDate;

    document.getElementById('startDate').value = date;
    document.getElementById('endDate').value = endDate || '';
    
    if (isEdit && prezziData[date]) {
        const prezzo = prezziData[date];
        document.getElementById('prezzo1').value = prezzo.prezzo_1 || 80;
        document.getElementById('prezzo2').value = prezzo.prezzo_2 || 100;
        document.getElementById('prezzo3').value = prezzo.prezzo_3 || 120;
        document.getElementById('prezzo4').value = prezzo.prezzo_4 || 140;
        document.getElementById('prezzo5').value = prezzo.prezzo_5 || 160;
        document.getElementById('prezzo6').value = prezzo.prezzo_6 || 180;
        document.getElementById('isClosed').checked = !!prezzo.is_closed;
        
        // Note solo se esiste
        const noteEl = document.getElementById('note');
        if (noteEl) {
            noteEl.value = prezzo.note || '';
        }
        
        document.getElementById('deletePrice').style.display = 'inline-block';
    } else {
        resetPriceForm();
        document.getElementById('deletePrice').style.display = 'none';
        document.getElementById('isClosed').checked = false;
    }
    
    const modal = new bootstrap.Modal(document.getElementById('priceModal'));
    modal.show();
}

// Reset form prezzi
function resetPriceForm() {
    document.getElementById('prezzo1').value = '80';
    document.getElementById('prezzo2').value = '100';
    document.getElementById('prezzo3').value = '120';
    document.getElementById('prezzo4').value = '140';
    document.getElementById('prezzo5').value = '160';
    document.getElementById('prezzo6').value = '180';
    
    const noteEl = document.getElementById('note');
    if (noteEl) {
        noteEl.value = '';
    }
}

// Salva prezzo
async function savePrice() {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    
    console.log('Salvataggio prezzi:', { startDate, endDate });
    
    if (!startDate) {
        showAlert('Seleziona una data di inizio', 'danger');
        return;
    }

    const dateRange = endDate ? `${startDate} to ${endDate}` : startDate;
    console.log('Range date inviato:', dateRange);


    const formData = {
        date_range: endDate ? `${startDate} to ${endDate}` : startDate,
        prezzo_1: parseFloat(document.getElementById('prezzo1').value) || 0,
        prezzo_2: parseFloat(document.getElementById('prezzo2').value) || 0,
        prezzo_3: parseFloat(document.getElementById('prezzo3').value) || 0,
        prezzo_4: parseFloat(document.getElementById('prezzo4').value) || 0,
        prezzo_5: parseFloat(document.getElementById('prezzo5').value) || 0,
        prezzo_6: parseFloat(document.getElementById('prezzo6').value) || 0,
        _token: csrfToken
    };

    // Assegna is_closed dopo la dichiarazione di formData
    formData.is_closed = document.getElementById('isClosed').checked ? 1 : 0;

    // Aggiungi note se esiste
    const noteEl = document.getElementById('note');
    if (noteEl) {
        formData.note = noteEl.value || '';
    }

    try {
        const response = await fetch(storeUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify(formData)
        });

        if (response.ok) {
            showAlert('Prezzi salvati con successo!', 'success');
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            const result = await response.json();
            showAlert(result.message || 'Errore durante il salvataggio', 'danger');
        }
    } catch (error) {
        console.error('Errore:', error);
        showAlert('Errore di connessione', 'danger');
    }
}


// Elimina prezzo
async function deletePrice() {
    if (!currentEditingDate) {
        showAlert('Nessun prezzo da eliminare', 'warning');
        return;
    }

    if (!confirm('Sei sicuro di voler eliminare questo prezzo?')) {
        return;
    }

    try {
        const url = deleteUrl.replace(':id', currentEditingDate);
        const response = await fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });

        if (response.ok) {
            showAlert('Prezzo eliminato!', 'success');
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            showAlert('Errore durante l\'eliminazione', 'danger');
        }
    } catch (error) {
        console.error('Errore:', error);
        showAlert('Errore di connessione', 'danger');
    }
}

// Imposta prezzi stagionali
function setSeasonalPrices(season) {
    const prezzi = season === 'estate' ? 
        { prezzo_1: 120, prezzo_2: 150, prezzo_3: 180, prezzo_4: 210, prezzo_5: 240, prezzo_6: 270 } :
        { prezzo_1: 60, prezzo_2: 80, prezzo_3: 100, prezzo_4: 120, prezzo_5: 140, prezzo_6: 160 };

    // Prima apri il modal con una data di oggi
    const today = new Date().toISOString().split('T')[0];
    openPriceModal(today);
    
    // Poi imposta i prezzi
    setTimeout(() => {
        document.getElementById('prezzo1').value = prezzi.prezzo_1;
        document.getElementById('prezzo2').value = prezzi.prezzo_2;
        document.getElementById('prezzo3').value = prezzi.prezzo_3;
        document.getElementById('prezzo4').value = prezzi.prezzo_4;
        document.getElementById('prezzo5').value = prezzi.prezzo_5;
        document.getElementById('prezzo6').value = prezzi.prezzo_6;
    }, 300);
    
    showAlert(`Prezzi ${season === 'estate' ? 'estivi' : 'invernali'} caricati nel form`, 'info');
}

// Imposta prezzi weekend
function setWeekendPrices() {
    alert('Funzione in sviluppo - per ora usa il modal per impostare i prezzi manualmente');
}

// Cancella tutti i prezzi
function clearAllPrices() {
    if (!confirm('Sei sicuro di voler cancellare TUTTI i prezzi? Questa azione non può essere annullata!')) {
        return;
    }
    
    alert('Funzione in sviluppo - per ora elimina i prezzi uno per uno dal calendario');
}

// Aggiorna statistiche
function updateStatistics() {
    const giorni = Object.keys(prezziData).length;
    document.getElementById('giorni-configurati').textContent = giorni;
    
    if (giorni === 0) {
        document.getElementById('prezzo-medio').textContent = '€0';
        document.getElementById('prezzo-massimo').textContent = '€0';
        document.getElementById('prezzo-minimo').textContent = '€0';
        return;
    }
    
    const prezzi = Object.values(prezziData).map(p => parseFloat(p.prezzo_1) || 0);
    const min = Math.min(...prezzi);
    const max = Math.max(...prezzi);
    const media = prezzi.reduce((a, b) => a + b, 0) / prezzi.length;
    
    document.getElementById('prezzo-medio').textContent = `€${media.toFixed(0)}`;
    document.getElementById('prezzo-massimo').textContent = `€${max.toFixed(0)}`;
    document.getElementById('prezzo-minimo').textContent = `€${min.toFixed(0)}`;
}

// Mostra alert
function showAlert(message, type) {
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            <i class="bi bi-${type === 'success' ? 'check-circle' : type === 'danger' ? 'exclamation-triangle' : 'info-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    document.getElementById('alertContainer').innerHTML = alertHtml;
    
    setTimeout(() => {
        const alert = document.querySelector('.alert');
        if (alert) {
            bootstrap.Alert.getOrCreateInstance(alert).close();
        }
    }, 5000);
}

// Setup event listeners
function setupEventListeners() {
    // Event listeners per i bottoni del modal
    document.getElementById('savePrice')?.addEventListener('click', savePrice);
    document.getElementById('deletePrice')?.addEventListener('click', deletePrice);
}
</script>
@endsection