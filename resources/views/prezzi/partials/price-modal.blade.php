<div class="modal fade" id="priceModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="bi bi-calendar-plus me-2"></i>
                    Gestione Prezzo
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Data Inizio</label>
                        <input type="date" class="form-control" id="startDate" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Data Fine (opzionale)</label>
                        <input type="date" class="form-control" id="endDate">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">
                            <i class="bi bi-person me-1"></i>1 Persona
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">€</span>
                            <input type="number" class="form-control" id="prezzo1" min="0" step="0.01" value="80">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">
                            <i class="bi bi-people me-1"></i>2 Persone
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">€</span>
                            <input type="number" class="form-control" id="prezzo2" min="0" step="0.01" value="100">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">
                            <i class="bi bi-people me-1"></i>3 Persone
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">€</span>
                            <input type="number" class="form-control" id="prezzo3" min="0" step="0.01" value="120">
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">
                            <i class="bi bi-people me-1"></i>4 Persone
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">€</span>
                            <input type="number" class="form-control" id="prezzo4" min="0" step="0.01" value="140">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">
                            <i class="bi bi-people me-1"></i>5 Persone
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">€</span>
                            <input type="number" class="form-control" id="prezzo5" min="0" step="0.01" value="160">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">
                            <i class="bi bi-people me-1"></i>6 Persone
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">€</span>
                            <input type="number" class="form-control" id="prezzo6" min="0" step="0.01" value="180">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <label class="form-label">Note (opzionale)</label>
                        <textarea class="form-control" id="note" rows="2" placeholder="Aggiungi note per questo periodo..."></textarea>
                    </div>
                </div>
                <div class="form-check mt-3 mb-2">
                    <input class="form-check-input" type="checkbox" id="isClosed">
                    <label class="form-check-label" for="isClosed">Chiudi prenotazioni</label>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x me-1"></i>Annulla
                </button>
                <button type="button" class="btn btn-danger" id="deletePrice" style="display: none;">
                    <i class="bi bi-trash me-1"></i>Elimina
                </button>
                <button type="button" class="btn btn-primary" id="savePrice">
                    <i class="bi bi-check me-1"></i>Salva
                </button>
            </div>
        </div>
    </div>
</div>