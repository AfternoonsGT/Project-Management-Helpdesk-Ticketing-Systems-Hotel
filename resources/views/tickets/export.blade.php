<!DOCTYPE html>
<html>
<head>
    <title>Laporan Tiket Kerusakan Hotel</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h2 { text-align: center; }
    </style>
</head>
<body>

    <h2>Laporan Kerusakan Fasilitas Hotel</h2>
    <p>Dicetak pada: {{ \Carbon\Carbon::now()->format('d M Y, H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No. Tiket</th>
                <th>Tanggal Lapor</th>
                <th>Judul / Masalah</th>
                <th>Lokasi</th>
                <th>Status</th>
                <th>Pelapor</th>
                <th>Teknisi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tickets as $index => $ticket)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $ticket->ticket_number }}</td>
                <td>{{ $ticket->created_at->format('d M Y') }}</td>
                <td>{{ $ticket->title }}</td>
                <td>{{ $ticket->location }}</td>
                <td>{{ strtoupper($ticket->status) }}</td>
                <td>{{ $ticket->reporter ? $ticket->reporter->name : '-' }}</td>
                <td>{{ $ticket->technician ? $ticket->technician->name : 'Belum Ditugaskan' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
