<!DOCTYPE html>
<html>

<head>
    <title>Memproses PDF...</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 9px; /* Ukuran font dioptimalkan untuk banyak kolom */
            color: #333;
            background-color: #fff;
        }

        #loading-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: white; z-index: 9999; display: flex;
            justify-content: center; align-items: center;
            font-size: 16px; font-weight: bold; color: #4b5563;
        }

        #area-laporan {
            padding: 20px;
            width: 100%;
            box-sizing: border-box;
        }

        h2 { text-align: center; margin-bottom: 5px; font-size: 18px; color: #0f2942; font-weight: bold; text-transform: uppercase; }
        .subtitle { text-align: center; margin-top: 0; color: #666; font-size: 10px; margin-bottom: 20px; }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        th, td {
            border: 1px solid #111;
            padding: 6px 4px;
            word-wrap: break-word;
            word-break: break-word;
            vertical-align: middle;
        }

        th {
            background-color: #0f2942;
            color: #ffffff;
            font-weight: bold;
            text-align: center;
            font-size: 9px;
            text-transform: uppercase;
        }

        tbody tr:nth-child(even) { background-color: #f9fafb; }
        tbody tr { page-break-inside: avoid; }

        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        .text-small { font-size: 8px; color: #666; }
        
        .desc-box {
            display: block;
            margin-top: 3px;
            padding-top: 3px;
            border-top: 1px dashed #ccc;
            font-size: 8px;
            color: #444;
        }
    </style>
</head>

<body>

    <div id="loading-overlay">⏳ Menyesuaikan format laporan... Mohon tunggu...</div>

    <div id="area-laporan">
        <h2>LAPORAN KERUSAKAN FASILITAS HOTEL PANGERAN</h2>
        <p class="subtitle">Dicetak pada: {{ \Carbon\Carbon::now()->timezone('Asia/Jakarta')->format('d F Y, H:i') }} WIB</p>

        <table>
            <thead>
                <tr>
                    <!-- Urutan Kolom Sesuai image_57655b.png -->
                    <th style="width: 3%;">NO</th>
                    <th style="width: 10%;">NO. TIKET</th>
                    <th style="width: 8%;">PELAPOR</th>
                    <th style="width: 7%;">TGL LAPOR</th>
                    <th style="width: 9%;">LOKASI</th>
                    <th style="width: 8%;">KATEGORI</th>
                    <th style="width: 8%;">LAYANAN</th>
                    <th style="width: 18%;">MASALAH & DESKRIPSI</th>
                    <th style="width: 8%;">TEKNISI</th>
                    <th style="width: 7%;">TGL SELESAI</th>
                    <th style="width: 6%;">STATUS</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tickets as $index => $ticket)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        
                        <td class="text-bold text-center">{{ $ticket->ticket_number }}</td>

                        <!-- Pelapor -->
                        <td class="text-center">{{ $ticket->reporter ? $ticket->reporter->name : '-' }}</td>
                        
                        <!-- Tgl Lapor -->
                        <td class="text-center">
                            {{ $ticket->created_at->format('d M Y') }}<br>
                            <span class="text-small">{{ $ticket->created_at->format('H:i') }}</span>
                        </td>
                        
                        <!-- Lokasi -->
                        <td>
                            <span class="text-bold">{{ $ticket->floor ?? 'Lantai -' }}</span><br>
                            <span style="font-size: 8px;">{{ $ticket->location }}</span>
                        </td>
                        
                        <td class="text-center">{{ $ticket->category ? $ticket->category->name : '-' }}</td>
                        
                        <td class="text-center">{{ ucfirst($ticket->service_type) }}</td>

                        <!-- Masalah & Deskripsi -->
                        <td>
                            <strong style="font-size: 10px;">{{ $ticket->title }}</strong>
                            <span class="desc-box">{{ $ticket->description ?? '-' }}</span>
                        </td>

                        <!-- Teknisi -->
                        <td class="text-center">{{ $ticket->technician ? $ticket->technician->name : '-' }}</td>

                        <!-- Tgl Selesai (Menggunakan completed_at) -->
                        <td class="text-center text-bold">
                            @if($ticket->completed_at)
                                {{ \Carbon\Carbon::parse($ticket->completed_at)->format('d M Y') }}<br>
                                <span class="text-small">{{ \Carbon\Carbon::parse($ticket->completed_at)->format('H:i') }}</span>
                            @else
                                -
                            @endif
                        </td>
                        
                        <td class="text-center text-bold">{{ strtoupper($ticket->status) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        window.onload = function() {
            var element = document.getElementById('area-laporan');
            var opt = {
                margin: 0.2,
                filename: 'Laporan_Helpdesk_Pangeran.pdf',
                image: { type: 'jpeg', quality: 1 },
                html2canvas: { scale: 2, useCORS: true },
                jsPDF: { unit: 'in', format: 'a4', orientation: 'landscape' }
            };

            html2pdf().set(opt).from(element).save().then(function() {
                window.history.back();
            });
        };
    </script>
</body>
</html>