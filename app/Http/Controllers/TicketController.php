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
        // Cek siapa yang sedang login
        $role = Auth::user()->role;

        // Logika untuk menampilkan tiket berdasarkan peran
        if ($role == 'admin') {
            // Admin melihat SEMUA tiket, diurutkan dari yang paling baru
            $tickets = Ticket::latest()->get();
        } elseif ($role == 'technician') {
            // Teknisi HANYA melihat tiket yang ditugaskan kepadanya
            $tickets = Ticket::where('technician_id', Auth::id())->latest()->get();
        } else {
            // Staff HANYA melihat tiket yang dia buat sendiri
            $tickets = Ticket::where('reporter_id', Auth::id())->latest()->get();
        }

        // Kirim data tiket ke halaman dashboard
        return view('dashboard', compact('tickets'));
    }

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

        return view('tickets.show', compact('ticket', 'technicians'));
    }

    // Fungsi untuk memproses penugasan teknisi
    public function assign(Request $request, Ticket $ticket)
    {
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
}
