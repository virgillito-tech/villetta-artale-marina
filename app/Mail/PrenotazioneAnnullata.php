<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Prenotazione; 

class PrenotazioneAnnullata extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * L'istanza della prenotazione.
     *
     * @var \App\Models\Prenotazione
     */
    public $prenotazione;

    /**
     * Crea una nuova istanza del messaggio.
     *
     * @param  \App\Models\Prenotazione  $prenotazione
     * @return void
     */
    public function __construct(Prenotazione $prenotazione)
    {
        $this->prenotazione = $prenotazione;
    }

    /**
     * Costruisci il messaggio.
     *
     * @return $this
     */
    public function build()
    {
        $locale = app()->getLocale();
        return $this->subject('Conferma Annullamento e Rimborso') // Oggetto dell'email
                    ->view($locale .'.emails.prenotazione_annullata'); // Il file Blade 
    }
}