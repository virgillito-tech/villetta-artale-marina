<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Sabre\VObject;
use Illuminate\Support\Facades\Log;
use App\Models\Prenotazione; // o modello per occupazione date

class SyncICalBookings extends Command
{
    protected $signature = 'sync:icalbookings';

    protected $description = 'Sincronizza le prenotazioni dal file iCal esterno';

    public function handle()
    {
        $icalUrl = 'https://ical.booking.com/v1/export?t=0349653a-79fc-498c-8ec1-3a5415f3ab44';

        try {
            $icalContent = @file_get_contents($icalUrl);
            if (!$icalContent) {
                Log::error("SyncICalBookings: impossibile scaricare il file iCal.");
                return 1;
            }

            $vcalendar = VObject\Reader::read($icalContent);

            if (!isset($vcalendar->VEVENT)) {
                Log::warning("SyncICalBookings: nessun evento VEVENT trovato.");
                return 0;
            }

            // Pulisci i dati vecchi (dipende come gestisci occupazione)
            // Prenotazione::truncate();

            foreach ($vcalendar->VEVENT as $event) {
                $start = $event->DTSTART->getDateTime()->format('Y-m-d');
                $end = $event->DTEND->getDateTime()->format('Y-m-d');

                // Qui puoi salvare queste date nel database, esempio:
                // Prenotazione::updateOrCreate(
                //    ['start_date' => $start, 'end_date' => $end],
                //    ['source' => 'ical']
                // );
            }

            $this->info('Sincronizzazione iCal completata con successo.');

        } catch (\Exception $e) {
            Log::error('SyncICalBookings: errore parsing iCal - ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}