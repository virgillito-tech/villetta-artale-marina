<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Prenotazione; // Importa il modello

class NotificaAnnullamentoAdmin extends Mailable
{
    use Queueable, SerializesModels;

    public $prenotazione;
    public $annullataDa;
    public $rimborso;

    /**
     * Crea una nuova istanza del messaggio.
     *
     * @param \App\Models\Prenotazione $prenotazione
     * @param string $annullataDa (Es. "Utente" o "Admin")
     * @param string $rimborso (Es. "Sì", "No", "N/A")
     */
    public function __construct(Prenotazione $prenotazione, $annullataDa = 'N/D', $rimborso = 'N/D')
    {
        $this->prenotazione = $prenotazione;
        $this->annullataDa = $annullataDa;
        $this->rimborso = $rimborso;
    }

    /**
     * Costruisci il messaggio.
     *
     * @return $this
     */
    public function build()
    {
        // Usa la vista HTML personalizzata invece del markdown
        return $this->subject('Notifica: Prenotazione Annullata')
                    ->view('emails.admin.prenotazione-annullata');
    }
}