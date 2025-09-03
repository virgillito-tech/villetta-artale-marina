<?php

namespace App\Mail;

use App\Models\Prenotazione;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PrenotazioneConfermata extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Prenotazione $prenotazione
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Prenotazione Confermata - Villetta Artale Marina',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.prenotazione-confermata',
        );
    }
}
