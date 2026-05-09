<?php

namespace App\Http\Controllers;
use App\Notifications\TicketNotification;
use Illuminate\Support\Facades\Notification;

use Illuminate\Http\Request;
use App\Models\Category;
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

        // Mengambil semua data kategori untuk ditampilkan di dropdown
        $categories = Category::all();

        $query = \App\Models\Ticket::query();

        if ($role == 'technician') {
            $query->where('technician_id', Auth::id());
        } elseif ($role == 'staff') {
            $query->where('reporter_id', Auth::id());
        }

        // Logika filter pencarian teks
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('ticket_number', 'like', '%' . $request->search . '%')
                    ->orWhere('location', 'like', '%' . $request->search . '%')
                    ->orWhere('floor', 'like', '%' . $request->search . '%');
            });
        }

        // Logika filter Bulan, Status, Kategori, dan Lantai
        if ($request->filled('year')) {
            $query->whereYear('created_at', $request->year);
        }
        if ($request->filled('month')) {
            $query->whereMonth('created_at', $request->month);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('floor')) {
            $query->where('floor', $request->floor);
        }


        $tickets = $query->latest()->paginate(5)->withQueryString();

        // Widget Statistik
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

        // 👇 TAMBAHAN DATA UNTUK GRAFIK (CHART) KATEGORI 👇
        $semuaKategori = \App\Models\Category::all();
        $chartLabels = [];
        $chartData = [];

        foreach($semuaKategori as $kategori) {
            $chartLabels[] = $kategori->name; // Masukkan nama kategori
            // Hitung berapa banyak tiket yang pakai kategori ini
            $chartData[] = \App\Models\Ticket::where('category_id', $kategori->id)->count();
        }
        // 👆 AKHIR TAMBAHAN DATA GRAFIK 👆

        // ... (Kodingan $chartLabels dan $chartData milikmu biarkan saja) ...

        // 👇 TAMBAHAN DATA UNTUK GRAFIK STATUS (PIE/DOUGHNUT) 👇
        $statusLabels = ['Open', 'On Progress', 'Resolved', 'Closed'];
        $statusData = [
            \App\Models\Ticket::where('status', 'open')->count(),
            \App\Models\Ticket::where('status', 'on_progress')->count(),
            \App\Models\Ticket::where('status', 'resolved')->count(),
            \App\Models\Ticket::where('status', 'closed')->count(),
        ];
        // 👆 AKHIR TAMBAHAN DATA STATUS 👆

        // Jangan lupa tambahkan 'chartLabels' dan 'chartData' ke dalam compact!
        return view('dashboard', compact('tickets', 'totalTickets', 'progressTickets', 'resolvedTickets', 'closedTickets', 'categories', 'chartLabels', 'chartData', 'statusLabels', 'statusData'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('tickets.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            'service_type' => 'required',
            'floor' => 'required',
            'location' => 'required',
            'title' => 'required',
            'description' => 'required',
            'image_before' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'priority' => 'required',
        ]);

        $imagePath = null;
        if ($request->hasFile('image_before')) {
            $imagePath = $request->file('image_before')->store('tickets', 'public');
        }

        $ticketNumber = 'TKT-' . date('Ymd') . '-' . strtoupper(Str::random(4));

        $ticket = Ticket::create([
            'ticket_number' => $ticketNumber,
            'reporter_id' => Auth::id(),
            'category_id' => $request->category_id,
            'service_type' => $request->service_type,
            'floor' => $request->floor,
            'location' => $request->location,
            'title' => $request->title,
            'description' => $request->description,
            'image_before' => $imagePath,
            'priority' => $request->priority,
            'status' => 'open',
        ]);

        TicketHistory::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'status' => 'open',
            'note' => 'Tiket baru dibuat oleh Staff.',
        ]);

// 👇 TRIGGER NOTIFIKASI KE ADMIN 👇
        // Cari semua user yang jabatannya admin
        $admins = User::where('role', 'admin')->get();

        // Kirim notifikasi ke mereka
        Notification::send($admins, new TicketNotification($ticket, 'Tiket Baru: ' . $ticket->title . ' dari ' . Auth::user()->name));
        //
        return redirect()->route('dashboard')->with('success', 'Laporan kerusakan berhasil dikirim!');
    }

    public function show(Ticket $ticket)
    {
        $technicians = User::where('role', 'technician')->get();
        $histories = TicketHistory::where('ticket_id', $ticket->id)->latest()->get();
        return view('tickets.show', compact('ticket', 'technicians', 'histories'));
    }

    public function assign(Request $request, Ticket $ticket)
    {
        abort_if(Auth::user()->role !== 'admin', 403, 'Akses Ditolak: Hanya Admin yang boleh menugaskan tiket!');

        $request->validate([
            'technician_id' => 'required'
        ]);

        $ticket->update([
            'technician_id' => $request->technician_id,
            'status' => 'assigned'
        ]);

        TicketHistory::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'status' => 'assigned',
            'note' => 'Admin menugaskan teknisi untuk perbaikan.',
        ]);

        // Kirim Notifikasi ke Teknisi yang ditugaskan
        $technician = User::find($request->technician_id);
        if ($technician) {
            Notification::send($technician, new TicketNotification($ticket, 'TUGAS BARU: ' . $ticket->title . ' menunggumu!'));
        }

        return back()->with('success', 'Teknisi berhasil ditugaskan!');
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        abort_if(Auth::user()->role !== 'technician' || Auth::id() !== $ticket->technician_id, 403, 'Akses Ditolak: Anda bukan teknisi yang ditugaskan untuk pekerjaan ini!');

        $request->validate([
            'status' => 'required',
            'note' => 'nullable|string',
            'image_after' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $imagePath = $ticket->image_after;
        if ($request->hasFile('image_after')) {
            $imagePath = $request->file('image_after')->store('tickets/after', 'public');
        }

        $ticket->update([
            'status' => $request->status,
            'image_after' => $imagePath,
        ]);

        TicketHistory::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'status' => $request->status,
            'note' => $request->note ?? 'Status tiket diperbarui menjadi ' . $request->status,
        ]);

        // ... (kode update status kamu sebelumnya) ...

        if ($request->status == 'resolved') {
            // Beritahu Admin bahwa teknisi sudah selesai dan minta diverifikasi
            $admins = User::where('role', 'admin')->get();
            \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\TicketNotification($ticket, 'MENUNGGU VERIFIKASI: Teknisi telah menyelesaikan tiket ' . $ticket->ticket_number));
        }

        return back()->with('success', 'Status pengerjaan berhasil diupdate!');
    }

    public function close(Request $request, Ticket $ticket)
    {
        abort_if(Auth::user()->role !== 'admin', 403, 'Akses Ditolak: Hanya Admin yang dapat menutup tiket!');
        $request->validate([
            'note' => 'nullable|string'
        ]);

        $ticket->update([
            'status' => 'closed'
        ]);

        TicketHistory::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'status' => 'closed',
            'note' => $request->note ?? 'Tiket telah diverifikasi dan ditutup oleh Admin.',
        ]);

        // Kirim Notifikasi ke Admin & Staff pembuat tiket
        $admins = User::where('role', 'admin')->get();
        $reporter = User::find($ticket->reporter_id);

        $pesan = 'Status tiket ' . $ticket->ticket_number . ' berubah menjadi ' . strtoupper($request->status);

        Notification::send($admins, new TicketNotification($ticket, $pesan));
        if ($reporter) {
            Notification::send($reporter, new TicketNotification($ticket, 'SELESAI: Laporan ' . $ticket->ticket_number . ' telah ditutup. Terima kasih!'));
        }

        // Beritahu Teknisi bahwa pekerjaannya di-ACC bos
        $technician = User::find($ticket->technician_id);
        if ($technician) {
            Notification::send($technician, new TicketNotification($ticket, 'DISETUJUI: Pekerjaanmu pada tiket ' . $ticket->ticket_number . ' telah di-ACC Admin.'));
        }

        return back()->with('success', 'Tiket berhasil ditutup secara permanen!');
    }

    public function reject(Request $request, Ticket $ticket)
    {
        abort_if(Auth::user()->role !== 'admin', 403, 'Akses Ditolak: Hanya Admin yang dapat menolak tiket!');

        $request->validate([
            'note' => 'required|string'
        ]);

        $ticket->update([
            'status' => 'on_progress'
        ]);

        TicketHistory::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'status' => 'on_progress',
            'note' => 'REVISI ADMIN: ' . $request->note,
        ]);

        // Beritahu teknisi bahwa pekerjaannya ditolak dan butuh revisi
        $technician = User::find($ticket->technician_id);
        if ($technician) {
            Notification::send($technician, new TicketNotification($ticket, 'REVISI: Laporan ' . $ticket->ticket_number . ' dikembalikan oleh Admin.'));
        }

        return back()->with('success', 'Tiket dikembalikan ke Teknisi untuk diperbaiki ulang!');
    }

    public function edit(Ticket $ticket)
    {
        abort_if(Auth::user()->role !== 'admin', 403, 'Akses Ditolak: Hanya Admin yang boleh mengedit tiket!');

        $categories = \App\Models\Category::all();
        return view('tickets.edit', compact('ticket', 'categories'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        abort_if(Auth::user()->role !== 'admin', 403, 'Akses Ditolak: Hanya Admin yang boleh mengedit tiket!');

        $request->validate([
           'category_id' => 'required',
            'service_type' => 'required',
            'title' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $ticket->update([
            'category_id' => $request->category_id,
            'service_type' => $request->service_type,
            'title' => $request->title,
            'location' => $request->location,
            'description' => $request->description,
        ]);

        return redirect()->route('tickets.show', $ticket->id)->with('success', 'Detail laporan tiket berhasil diperbarui!');
    }

    public function destroy(Ticket $ticket)
    {
        abort_if(Auth::user()->role !== 'admin', 403, 'Akses Ditolak: Hanya Admin yang boleh menghapus tiket!');

        if ($ticket->image_before) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($ticket->image_before);
        }
        if ($ticket->image_after) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($ticket->image_after);
        }

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

        if ($role == 'technician') {
            $query->where('technician_id', \Illuminate\Support\Facades\Auth::id());
        } elseif ($role == 'staff') {
            $query->where('reporter_id', \Illuminate\Support\Facades\Auth::id());
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('ticket_number', 'like', '%' . $request->search . '%')
                    ->orWhere('location', 'like', '%' . $request->search . '%')
                    ->orWhere('floor', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('floor')) {
            $query->where('floor', $request->floor);
        }
        if ($request->filled('month')) {
            $query->whereMonth('created_at', $request->month);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $tickets = $query->latest()->get();

        if ($request->type == 'pdf') {
            return view('tickets.export-pdf', compact('tickets'));
        }

        if ($request->type == 'excel') {
            return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\TicketsExport($tickets), 'Laporan_Tiket_Hotel.xlsx');
        }

        return back();
    }

// Tambahkan tipe data "string" pada $id untuk menghilangkan peringatan pertama
    public function readNotification(string $id)
    {
        // Trik ajaib untuk memberitahu VS Code bahwa ini adalah model User
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Gunakan $user->notifications(), BUKAN Auth::user()->notifications() untuk menghilangkan peringatan kedua
        $notification = $user->notifications()->findOrFail($id);

        $notification->markAsRead();

        return redirect($notification->data['url']);
    }


}
