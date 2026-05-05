<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize; // Agar lebar kolom otomatis rapi

class TicketsExport implements FromView, ShouldAutoSize
{
    public $tickets;

    // Menangkap data tiket dari Controller
    public function __construct($tickets)
    {
        $this->tickets = $tickets;
    }

    public function view(): View
    {
        // Akan mengambil desain dari resources/views/tickets/export.blade.php
        return view('tickets.export', [
            'tickets' => $this->tickets
        ]);
    }
}
