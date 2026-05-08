<!DOCTYPE html>
<html>

<head>
    <title>Memproses PDF...</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <style>
        /* Reset margin browser */
        body {
            margin: 0;
            padding: 0;
            font-family: sans-serif;
            font-size: 11px;
            background-color: #fff;
        }

        /* 👇 LAYAR PUTIH PENUTUP (OVERLAY) 👇 */
        #loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: white;
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 18px;
            font-weight: bold;
            color: #4b5563;
        }

        /* Area laporan di posisi normal (tidak dilempar ke -9999px lagi) */
        #area-laporan {
            padding: 20px;
            width: 100%;
            max-width: 100%;
            background-color: white;
            box-sizing: border-box;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            table-layout: fixed;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
            word-wrap: break-word;
            word-break: break-word;
            overflow-wrap: anywhere;
            vertical-align: top;
        }

        tbody tr {
            page-break-inside: avoid;
        }

        th {
            background-color: #f2f2f2;
        }

        h2 {
            text-align: center;
            margin-bottom: 5px;
        }
    </style>
</head>

<body>

    <!-- Layar loading yang menutupi tabel -->
    <div id="loading-overlay">⏳ Sedang memproses dokumen PDF... Mohon tunggu...</div>

    <!-- Tabel dibiarkan di posisi kiri atas (Normal) -->
    <div id="area-laporan">
        <h2>Laporan Kerusakan Fasilitas Hotel</h2>
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->format('d M Y, H:i') }}</p>

        <table>
            <thead>
                <tr>
                    <th style="width: 4%;">No</th>
                    <th style="width: 14%;">No. Tiket</th>
                    <th style="width: 10%;">Tgl Lapor</th>
                    <th style="width: 10%;">Kategori</th>
                    <th style="width: 16%;">Judul / Masalah</th>
                    <th style="width: 12%;">Jenis Layanan</th>
                    <th style="width: 10%;">Lokasi</th>
                    <th style="width: 8%;">Status</th>
                    <th style="width: 13%;">Pelapor</th>
                    <th style="width: 13%;">Teknisi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tickets as $index => $ticket)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $ticket->ticket_number }}</td>
                        <td>{{ $ticket->created_at->format('d M Y') }}</td>
                        <!-- CARA YANG BENAR -->
                        <td>{{ $ticket->category ? $ticket->category->name : 'Tidak Ada Kategori' }}</td>
                        <td>{{ $ticket->title }}</td>

                        <!-- Penerjemah Jenis Layanan -->
                        <td>
                            @if ($ticket->service_type == 'perbaikan')
                                🚨 Perbaikan
                            @elseif($ticket->service_type == 'rutin')
                                🔧 Servis Rutin
                            @else
                                -
                            @endif
                        </td>

                        <!-- 👇 GABUNGAN LANTAI DAN LOKASI UNTUK PDF 👇 -->
                        <td>
                            <b>{{ $ticket->floor }}</b><br>
                            {{ $ticket->location }}
                        </td>
                        <td>{{ strtoupper($ticket->status) }}</td>
                        <td>{{ $ticket->reporter ? $ticket->reporter->name : '-' }}</td>
                        <td>{{ $ticket->technician ? $ticket->technician->name : 'Belum Ditugaskan' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Script Pengeksekusi -->
    <script>
        window.onload = function() {
            var element = document.getElementById('area-laporan');

            var opt = {
                margin: 0.3,
                filename: 'Laporan_Tiket_Hotel.pdf',
                image: {
                    type: 'jpeg',
                    quality: 1
                },
                // Kamera tidak akan geser karena elemen di posisi normal!
                html2canvas: {
                    scale: 2
                },
                jsPDF: {
                    unit: 'in',
                    format: 'a4',
                    orientation: 'landscape'
                },
                pagebreak: {
                    mode: ['css', 'legacy']
                }
            };

            html2pdf().set(opt).from(element).save().then(function() {
                window.history.back(); // Otomatis kembali setelah download selesai
            });
        };
    </script>

</body>

</html>
