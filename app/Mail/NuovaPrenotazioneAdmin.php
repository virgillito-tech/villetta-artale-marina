<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
// NON PIÙ NECESSARI: use Illuminate\Mail\Mailables\Content;
// NON PIÙ NECESSARI: use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Prenotazione;

class NuovaPrenotazioneAdmin extends Mailable
{
    use Queueable, SerializesModels;

    public $prenotazione;

    /**
     * Create a new message instance.
     */
    public function __construct(Prenotazione $prenotazione)
    {
        $this->prenotazione = $prenotazione;
    }

    /**
     * 2. USA SOLO IL METODO BUILD
     * (Tutto il resto è stato cancellato)
     */
    public function build()
    {
        return $this->subject('Nuova Prenotazione Ricevuta - Villetta Artale Marina')
                    ->markdown('emails.admin.nuova-prenotazione');
    }

    /*
     * 3. CANCELLA LE FUNZIONI content(), envelope(), e attachments()
     */
}