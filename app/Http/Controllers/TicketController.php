<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category; // Panggil model Category
use App\Models\Ticket;
use App\Models\TicketHistory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class TicketController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $role = Auth::user()->role;

        // ==========================================
        // 1. SIAPKAN MESIN PENCARI (QUERY BUILDER)
        // ==========================================
        $query = \App\Models\Ticket::query();

        // Filter awal: Admin bebas, Teknisi dan Staff hanya lihat miliknya
        if ($role == 'technician') {
            $query->where('technician_id', Auth::id());
        } elseif ($role == 'staff') {
            $query->where('reporter_id', Auth::id());
        }

        // ==========================================
        // 2. TERAPKAN FILTER DARI DROPDOWN & SEARCH
        // ==========================================
        // Jika ada pencarian teks (filled = tidak kosong)
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('ticket_number', 'like', '%' . $request->search . '%')
                  ->orWhere('location', 'like', '%' . $request->search . '%');
            });
        }

        // Jika filter Bulan dipilih
        if ($request->filled('month')) {
            $query->whereMonth('created_at', $request->month);
        }

        // Jika filter Status dipilih
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // ==========================================
        // 3. EKSEKUSI DATA TABEL (PENTING!)
        // ==========================================
        // Di sinilah data diambil. Jangan ada kodingan $tickets = ... lagi setelah baris ini!
        $tickets = $query->latest()->paginate(5)->withQueryString();


        // ==========================================
        // 4. HITUNG WIDGET STATISTIK KOTAK DI ATAS
        // ==========================================
        // Hitungan ini dibiarkan menghitung SEMUA data agar angka di kotak atas tidak ikut berubah/hilang saat kita mencari data di tabel.
        if ($role == 'admin') {
            $totalTickets = \App\Models\Ticket::count();
            $progressTickets = \App\Models\Ticket::whereIn('status', ['assigned', 'on_progress'])->count();
            $resolvedTickets = \App\Models\Ticket::where('status', 'resolved')->count();
            $closedTickets = \App\Models\Ticket::where('status', 'closed')->count();

        } elseif ($role == 'technician') {
            $totalTickets = \App\Models\Ticket::where('technician_id', Auth::id())->count();
            $progressTickets = \App\Models\Ticket::where('technician_id', Auth::id())->whereIn('status', ['assigned', 'on_progress'])->count();
            $resolvedTickets = \App\Models\Ticket::where('technician_id', Auth::id())->where('status', 'resolved')->count();
            $closedTickets = \App\Models\Ticket::where('technician_id', Auth::id())->where('status', 'closed')->count();

        } else {
            $totalTickets = \App\Models\Ticket::where('reporter_id', Auth::id())->count();
            $progressTickets = \App\Models\Ticket::where('reporter_id', Auth::id())->whereIn('status', ['assigned', 'on_progress'])->count();
            $resolvedTickets = \App\Models\Ticket::where('reporter_id', Auth::id())->where('status', 'resolved')->count();
            $closedTickets = \App\Models\Ticket::where('reporter_id', Auth::id())->where('status', 'closed')->count();
        }

        return view('dashboard', compact('tickets', 'totalTickets', 'progressTickets', 'resolvedTickets', 'closedTickets'));
    }
    // // Fungsi untuk menampilkan halaman utama / dashboard
    // public function index()
    // {
    //     // Cek siapa yang sedang login
    //     $role = Auth::user()->role;

    //     // Logika untuk menampilkan tiket berdasarkan peran
    //     if ($role == 'admin') {
    //         // Admin melihat SEMUA tiket, diurutkan dari yang paling baru
    //         $tickets = Ticket::latest()->paginate(5);
    //     } elseif ($role == 'technician') {
    //         // Teknisi HANYA melihat tiket yang ditugaskan kepadanya
    //         $tickets = Ticket::where('technician_id', Auth::id())->latest()->paginate(5);
    //     } else {
    //         // Staff HANYA melihat tiket yang dia buat sendiri
    //         $tickets = Ticket::where('reporter_id', Auth::id())->latest()->paginate(5);
    //     }

    //     // Kirim data tiket ke halaman dashboard
    //     return view('dashboard', compact('tickets'));
    // }


    // Fungsi untuk menampilkan form
    public function create()
    {
        // Ambil semua data kategori dari database
        $categories = Category::all();

        // Kirim data kategori ke file tampilan (view)
        return view('tickets.create', compact('categories'));
    }

    // Fungsi untuk memproses data dari form
    public function store(Request $request)
    {
        // 1. Validasi inputan (pastikan tidak ada yang kosong)
        $request->validate([
            'category_id' => 'required',
            'location' => 'required',
            'title' => 'required',
            'description' => 'required',
            'image_before' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Maksimal 2MB
            'priority' => 'required',
        ]);

        // 2. Proses Upload Foto (jika ada)
        $imagePath = null;
        if ($request->hasFile('image_before')) {
            // Simpan foto ke folder 'public/tickets'
            $imagePath = $request->file('image_before')->store('tickets', 'public');
        }

        // 3. Generate Nomor Tiket Otomatis (Contoh: TKT-20260502-ABCD)
        $ticketNumber = 'TKT-' . date('Ymd') . '-' . strtoupper(Str::random(4));

        // 4. Simpan ke tabel 'tickets'
        $ticket = Ticket::create([
            'ticket_number' => $ticketNumber,
            'reporter_id' => Auth::id(), // ID staff yang sedang login
            'category_id' => $request->category_id,
            'location' => $request->location,
            'title' => $request->title,
            'description' => $request->description,
            'image_before' => $imagePath,
            'priority' => $request->priority,
            'status' => 'open', // Status awal pasti 'open'
        ]);

        // 5. Simpan jejak awal ke tabel 'ticket_histories'
        TicketHistory::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'status' => 'open',
            'note' => 'Tiket baru dibuat oleh Staff.',
        ]);

        // 6. Kembalikan user ke halaman dashboard dengan pesan sukses
        return redirect()->route('dashboard')->with('success', 'Laporan kerusakan berhasil dikirim!');
    }

    // Fungsi untuk menampilkan halaman detail
    public function show(Ticket $ticket)
    {
        // Ambil daftar user yang memiliki role 'technician'
        $technicians = User::where('role', 'technician')->get();

        // AMBIL DATA RIWAYAT CATATAN TIKET INI (Urutkan dari yang terbaru)
        $histories = TicketHistory::where('ticket_id', $ticket->id)->latest()->get();

        // Tambahkan $histories ke dalam compact()
        return view('tickets.show', compact('ticket', 'technicians', 'histories'));
    }

    // Fungsi untuk memproses penugasan teknisi
   public function assign(Request $request, Ticket $ticket)
    {
        // KODE KEAMANAN: Tolak jika yang akses bukan admin
        abort_if(Auth::user()->role !== 'admin', 403, 'Akses Ditolak: Hanya Admin yang boleh menugaskan tiket!');

        // ... (biarkan kodingan validasi dan update di bawahnya tetap ada)

        $request->validate([
            'technician_id' => 'required'
        ]);

        // Update data tiket
        $ticket->update([
            'technician_id' => $request->technician_id,
            'status' => 'assigned'
        ]);

        // Catat di tabel history
        TicketHistory::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'status' => 'assigned',
            'note' => 'Admin menugaskan teknisi untuk perbaikan.',
        ]);

        return back()->with('success', 'Teknisi berhasil ditugaskan!');
    }

    // Fungsi untuk Teknisi mengupdate status dan upload foto
   public function updateStatus(Request $request, Ticket $ticket)
    {
        // KODE KEAMANAN: Tolak jika bukan teknisi ATAU bukan teknisi yang ditugaskan di tiket ini
        abort_if(Auth::user()->role !== 'technician' || Auth::id() !== $ticket->technician_id, 403, 'Akses Ditolak: Anda bukan teknisi yang ditugaskan untuk pekerjaan ini!');

        // ... (biarkan kodingan validasi dan update di bawahnya tetap ada)
        // Validasi input
        $request->validate([
            'status' => 'required',
            'note' => 'nullable|string',
            'image_after' => 'nullable|image|mimes:jpeg,png,jpg|max:2048' // Maksimal 2MB
        ]);

        // Proses foto sesudah perbaikan (jika teknisi menguploadnya)
        $imagePath = $ticket->image_after; // Pertahankan foto lama jika tidak ada yang baru
        if ($request->hasFile('image_after')) {
            $imagePath = $request->file('image_after')->store('tickets/after', 'public');
        }

        // Update data tiket
        $ticket->update([
            'status' => $request->status,
            'image_after' => $imagePath,
        ]);

        // Catat ke tabel history
        TicketHistory::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'status' => $request->status,
            'note' => $request->note ?? 'Status tiket diperbarui menjadi ' . $request->status,
        ]);

        return back()->with('success', 'Status pengerjaan berhasil diupdate!');
    }

    // Fungsi khusus Admin untuk menutup tiket (Closed)
   public function close(Request $request, Ticket $ticket)
    {
        // KODE KEAMANAN: Tolak jika yang akses bukan admin
        abort_if(Auth::user()->role !== 'admin', 403, 'Akses Ditolak: Hanya Admin yang dapat menutup tiket!');
        // Validasi opsional untuk catatan penutup
        $request->validate([
            'note' => 'nullable|string'
        ]);

        // Update status tiket menjadi closed
        $ticket->update([
            'status' => 'closed'
        ]);

        // Catat ke tabel history
        TicketHistory::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(), // ID Admin yang menutup tiket
            'status' => 'closed',
            'note' => $request->note ?? 'Tiket telah diverifikasi dan ditutup oleh Admin.',
        ]);

        return back()->with('success', 'Tiket berhasil ditutup secara permanen!');
    }

    // Fungsi khusus Admin untuk mengembalikan tiket ke Teknisi (Revisi)
    public function reject(Request $request, Ticket $ticket)
    {
        // KODE KEAMANAN: Tolak jika yang akses bukan admin
        abort_if(Auth::user()->role !== 'admin', 403, 'Akses Ditolak: Hanya Admin yang dapat menolak tiket!');

        // Untuk penolakan, catatan WAJIB diisi agar teknisi tahu apa yang salah
        $request->validate([
            'note' => 'required|string'
        ]);

        // Kembalikan status tiket ke 'on_progress'
        $ticket->update([
            'status' => 'on_progress'
        ]);

        // Catat ke tabel history dengan label REVISI
        TicketHistory::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(), // ID Admin
            'status' => 'on_progress',
            'note' => 'REVISI ADMIN: ' . $request->note,
        ]);

        return back()->with('success', 'Tiket dikembalikan ke Teknisi untuk diperbaiki ulang!');
    }
    // ==========================================
    // FITUR KHUSUS ADMIN: EDIT & DELETE
    // ==========================================

    // 1. Menampilkan halaman form edit
    public function edit(Ticket $ticket)
    {
        abort_if(Auth::user()->role !== 'admin', 403, 'Akses Ditolak: Hanya Admin yang boleh mengedit tiket!');
        return view('tickets.edit', compact('ticket'));
    }

    // 2. Memproses data yang diubah
    public function update(Request $request, Ticket $ticket)
    {
        abort_if(Auth::user()->role !== 'admin', 403, 'Akses Ditolak: Hanya Admin yang boleh mengedit tiket!');

        $request->validate([
            'title' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $ticket->update([
            'title' => $request->title,
            'location' => $request->location,
            'description' => $request->description,
        ]);

        return redirect()->route('tickets.show', $ticket->id)->with('success', 'Detail laporan tiket berhasil diperbarui!');
    }

    // 3. Menghapus tiket secara permanen
    public function destroy(Ticket $ticket)
    {
        abort_if(Auth::user()->role !== 'admin', 403, 'Akses Ditolak: Hanya Admin yang boleh menghapus tiket!');

        // Hapus foto dari penyimpanan folder (agar memori server hotel tidak penuh)
        if ($ticket->image_before) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($ticket->image_before);
        }
        if ($ticket->image_after) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($ticket->image_after);
        }

        // Hapus data tiket beserta riwayatnya (otomatis terhapus jika di migration pakai onDelete cascade, tapi kita hapus manual untuk aman)
        \App\Models\TicketHistory::where('ticket_id', $ticket->id)->delete();
        $ticket->delete();

        return redirect()->route('dashboard')->with('success', 'Tiket dan fotonya berhasil dihapus secara permanen!');
    }
    // ==========================================
    // FITUR EXPORT PDF & EXCEL
    // ==========================================
    public function export(\Illuminate\Http\Request $request)
    {
        $role = \Illuminate\Support\Facades\Auth::user()->role;
        $query = \App\Models\Ticket::query();

        // Sama seperti index, sesuaikan role
        if ($role == 'technician') {
            $query->where('technician_id', \Illuminate\Support\Facades\Auth::id());
        } elseif ($role == 'staff') {
            $query->where('reporter_id', \Illuminate\Support\Facades\Auth::id());
        }

        // Terapkan filter yang sama jika ada
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('ticket_number', 'like', '%' . $request->search . '%')
                  ->orWhere('location', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->filled('month')) {
            $query->whereMonth('created_at', $request->month);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Ambil SEMUA datanya (pakai get(), BUKAN paginate() agar tidak terpotong per 5 baris)
        $tickets = $query->latest()->get();

        // Jika tombol cetak ditekan adalah PDF
        if ($request->type == 'pdf') {
            // Tiga baris ini adalah "Suntikan Tenaga" agar server tidak pingsan
            ini_set('max_execution_time', 300);
            ini_set('memory_limit', '512M');
            libxml_use_internal_errors(true);
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('tickets.export', compact('tickets'));
            // Set ukuran kertas A4 mendatar (Landscape)
            $pdf->setPaper('A4', 'landscape');
            return $pdf->download('Laporan_Tiket_Hotel.pdf');
        }

        // Jika tombol cetak ditekan adalah Excel
        if ($request->type == 'excel') {
            return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\TicketsExport($tickets), 'Laporan_Tiket_Hotel.xlsx');
        }

        return back();
    }
}
