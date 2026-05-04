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
    // Fungsi untuk menampilkan halaman utama / dashboard
    public function index()
    {
        $role = Auth::user()->role;

        // Logika untuk mengambil data tiket dan statistik berdasarkan peran
        if ($role == 'admin') {
            // Data Tabel (Dibatasi 5 per halaman)
            $tickets = Ticket::latest()->paginate(5);

            // Data Widget Statistik (Menghitung seluruh data)
            $totalTickets = Ticket::count();
            $progressTickets = Ticket::whereIn('status', ['assigned', 'on_progress'])->count();
            $resolvedTickets = Ticket::where('status', 'resolved')->count();
            $closedTickets = Ticket::where('status', 'closed')->count();

        } elseif ($role == 'technician') {
            $tickets = Ticket::where('technician_id', Auth::id())->latest()->paginate(5);

            $totalTickets = Ticket::where('technician_id', Auth::id())->count();
            $progressTickets = Ticket::where('technician_id', Auth::id())->whereIn('status', ['assigned', 'on_progress'])->count();
            $resolvedTickets = Ticket::where('technician_id', Auth::id())->where('status', 'resolved')->count();
            $closedTickets = Ticket::where('technician_id', Auth::id())->where('status', 'closed')->count();

        } else {
            // Untuk Staff
            $tickets = Ticket::where('reporter_id', Auth::id())->latest()->paginate(5);

            $totalTickets = Ticket::where('reporter_id', Auth::id())->count();
            $progressTickets = Ticket::where('reporter_id', Auth::id())->whereIn('status', ['assigned', 'on_progress'])->count();
            $resolvedTickets = Ticket::where('reporter_id', Auth::id())->where('status', 'resolved')->count();
            $closedTickets = Ticket::where('reporter_id', Auth::id())->where('status', 'closed')->count();
        }

        // Kirim semua data ke view
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
}
