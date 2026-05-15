<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class TicketsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $tickets;

    public function __construct($tickets)
    {
        $this->tickets = $tickets;
    }

    public function collection()
    {
        return $this->tickets;
    }

    // 1. Judul Kolom (Sekarang jadi baris ke-3 karena ada judul di atas)
    public function headings(): array
    {
        return [
            'NO. TIKET',
            'PELAPOR',
            'TANGGAL LAPOR',
            'LOKASI',
            'KATEGORI',
            'JENIS LAYANAN',
            'MASALAH & DESKRIPSI',
            'TEKNISI',
            'TANGGAL SELESAI',
            'STATUS'
        ];
    }

    // 2. Pemetaan Data
    public function map($ticket): array
    {
        return [
            $ticket->ticket_number,
            $ticket->reporter ? $ticket->reporter->name : '-',
            $ticket->created_at->format('d/m/Y H:i'),
            ($ticket->floor ?? 'Lantai -') . ' (' . $ticket->location . ')',
            $ticket->category ? $ticket->category->name : '-',
            ucfirst($ticket->service_type),
            $ticket->title . ' - ' . ($ticket->description ?? '-'),
            $ticket->technician ? $ticket->technician->name : '-',
            $ticket->completed_at ? \Carbon\Carbon::parse($ticket->completed_at)->format('d/m/Y H:i') : '-',
            strtoupper($ticket->status),
        ];
    }

    // 3. Menambahkan Judul Laporan di Baris Atas (Sesuai image_574eb3.png)
  public function styles(Worksheet $sheet)
    {
        // 1. Tambahkan 3 baris kosong di atas (biar lebih lega)
        $sheet->insertNewRowBefore(1, 3);

        // 2. Atur Tinggi Baris (Row Height) agar tidak berdempetan
        $sheet->getRowDimension('1')->setRowHeight(30); // Baris Judul
        $sheet->getRowDimension('2')->setRowHeight(20); // Baris Waktu Cetak
        $sheet->getRowDimension('4')->setRowHeight(25); // Baris Header Tabel

        // ISI JUDUL (Baris 1)
        $sheet->setCellValue('A1', 'LAPORAN KERUSAKAN FASILITAS HOTEL PANGERAN');
        $sheet->mergeCells('A1:J1');

        // ISI WAKTU CETAK (Baris 2)
        $waktuCetak = 'Dicetak pada: ' . \Carbon\Carbon::now()->timezone('Asia/Jakarta')->format('d M Y, H:i') . ' WIB';
        $sheet->setCellValue('A2', $waktuCetak);
        $sheet->mergeCells('A2:J2');

        // STYLE JUDUL UTAMA
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => '0F2942']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);

        // STYLE WAKTU CETAK
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['size' => 10, 'color' => ['rgb' => '666666']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_TOP],
        ]);

        // STYLE HEADER TABEL (Sekarang turun ke Baris 4 karena ada jarak)
        $sheet->getStyle('A4:J4')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0F2942']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);

        // Borders untuk data (Mulai dari baris 4)
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle('A4:J' . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        
        // Atur agar teks di dalam sel data juga berada di tengah secara vertikal (biar rapi)
        $sheet->getStyle('A5:J' . $lastRow)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        return [];
    }
}