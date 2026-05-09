<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TicketNotification extends Notification
{
    use Queueable;

    protected $ticket;
    protected $message;

    // Menerima data tiket dan pesan
    public function __construct($ticket, $message)
    {
        $this->ticket = $ticket;
        $this->message = $message;
    }

    // WAJIB 'database' agar masuk ke lonceng, bukan 'mail'
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    // Format data yang disimpan di tabel notifikasi
    public function toArray(object $notifiable): array
    {
        return [
            'ticket_id' => $this->ticket->id,
            'ticket_number' => $this->ticket->ticket_number,
            'message' => $this->message,
            'url' => route('tickets.show', $this->ticket->id)
        ];
    }
}
