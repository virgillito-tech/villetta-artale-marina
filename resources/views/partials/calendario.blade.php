@extends('layouts.admin')

@section('content')
{{-- partials/calendario.blade.php --}}
<div class="calendar-wrap position-relative my-4">
    <div id="calendar-loading" class="position-absolute top-50 start-50 translate-middle d-none">
        <div class="spinner-border text-primary" role="status" aria-hidden="true"></div>
    </div>

    <div id="calendar"></div>
</div>

<!-- Modal per dettaglio evento -->
<div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 id="eventModalLabel" class="modal-title"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
      </div>
      <div class="modal-body">
        <p id="eventDates" class="mb-2 text-muted"></p>
        <p id="eventDesc" class="mb-0"></p>
      </div>
      <div class="modal-footer">
        <a id="eventLink" class="btn btn-primary d-none" target="_blank">Dettagli</a>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
      </div>
    </div>
  </div>
</div>

<!-- FullCalendar (includi solo se non è già incluso globalmente) -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // eventi passati dal controller (sempre almeno array)
    const events = @json($prenotazioni ?? []);

    const calendarEl = document.getElementById('calendar');
    const loadingEl = document.getElementById('calendar-loading');

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listWeek'
        },
        buttonText: { today: 'Oggi', month: 'Mese', week: 'Settimana', list: 'Lista' },
        locale: 'it',
        themeSystem: 'bootstrap5',
        navLinks: true,
        nowIndicator: true,
        dayMaxEventRows: 3,
        stickyHeaderDates: true,
        events: events,
        height: 'auto',
        aspectRatio: 1.35,
        loading: function(isLoading) {
            if (isLoading) {
                loadingEl.classList.remove('d-none');
            } else {
                loadingEl.classList.add('d-none');
            }
        },
        eventDidMount: function(info) {
            // stile pill, tooltip nativo
            info.el.classList.add('fc-event-pill');
            info.el.setAttribute('title', info.event.title);
        },
        eventClick: function(info) {
            info.jsEvent.preventDefault();

            const e = info.event;
            document.getElementById('eventModalLabel').textContent = e.title || 'Dettaglio';
            const s = e.start ? new Date(e.start).toLocaleString('it-IT', { dateStyle: 'medium', timeStyle: 'short' }) : '';
            const en = e.end ? new Date(e.end).toLocaleString('it-IT', { dateStyle: 'medium', timeStyle: 'short' }) : '';
            document.getElementById('eventDates').textContent = s + (en ? ' — ' + en : '');
            const desc = e.extendedProps?.description || e.extendedProps?.note || (e.extendedProps?.source || '');
            document.getElementById('eventDesc').textContent = desc || 'Nessuna informazione aggiuntiva';

            const linkEl = document.getElementById('eventLink');
            const url = e.extendedProps?.url || e.url || null;
            if (url) {
                linkEl.href = url;
                linkEl.classList.remove('d-none');
            } else {
                linkEl.classList.add('d-none');
            }

            const modal = new bootstrap.Modal(document.getElementById('eventModal'));
            modal.show();
        },
        windowResize: function() {
            calendar.setOption('height', 'auto');
        }
    });

    calendar.render();
});
</script>
@endsection