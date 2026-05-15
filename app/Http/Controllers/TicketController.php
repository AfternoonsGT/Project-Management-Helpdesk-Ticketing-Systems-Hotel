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

        // Logika filter LENGKAP
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
        // TAMBAHAN: Filter Jenis Layanan
        if ($request->filled('service_type')) {
            $query->where('service_type', $request->service_type);
        }

        $tickets = $query->latest()->paginate(5)->withQueryString();

        // Widget Statistik
        if ($role == 'admin') {
            $totalTickets = \App\Models\Ticket::count();
            $progressTickets = \App\Models\Ticket::whereIn('status', ['assigned', 'on_progress'])->count();
            $resolvedTickets = \App\Models\Ticket::where('status', 'resolved')->count();
            $closedTickets = \App\Models\Ticket::where('status', 'closed')->count();
            $openTickets = \App\Models\Ticket::where('status', 'open')->count();
        } elseif ($role == 'technician') {
            $totalTickets = \App\Models\Ticket::where('technician_id', Auth::id())->count();
            $progressTickets = \App\Models\Ticket::where('technician_id', Auth::id())->whereIn('status', ['assigned', 'on_progress'])->count();
            $resolvedTickets = \App\Models\Ticket::where('technician_id', Auth::id())->where('status', 'resolved')->count();
            $closedTickets = \App\Models\Ticket::where('technician_id', Auth::id())->where('status', 'closed')->count();
            $openTickets = \App\Models\Ticket::where('technician_id', Auth::id())->where('status', 'open')->count();
        } else {
            $totalTickets = \App\Models\Ticket::where('reporter_id', Auth::id())->count();
            $progressTickets = \App\Models\Ticket::where('reporter_id', Auth::id())->whereIn('status', ['assigned', 'on_progress'])->count();
            $resolvedTickets = \App\Models\Ticket::where('reporter_id', Auth::id())->where('status', 'resolved')->count();
            $closedTickets = \App\Models\Ticket::where('reporter_id', Auth::id())->where('status', 'closed')->count();
            $openTickets = \App\Models\Ticket::where('reporter_id', Auth::id())->where('status', 'open')->count();
        }

        // Data Grafik Kategori
        $semuaKategori = \App\Models\Category::all();
        $chartLabels = [];
        $chartData = [];
        foreach($semuaKategori as $kategori) {
            $chartLabels[] = $kategori->name;
            $chartData[] = \App\Models\Ticket::where('category_id', $kategori->id)->count();
        }

        // Data Grafik Status
        $statusLabels = ['Open', 'On Progress', 'Resolved', 'Closed'];
        $statusData = [
            \App\Models\Ticket::where('status', 'open')->count(),
            \App\Models\Ticket::where('status', 'on_progress')->count(),
            \App\Models\Ticket::where('status', 'resolved')->count(),
            \App\Models\Ticket::where('status', 'closed')->count(),
        ];

        return view('dashboard', compact('tickets', 'openTickets', 'progressTickets', 'resolvedTickets', 'closedTickets', 'categories', 'chartLabels', 'chartData', 'statusLabels', 'statusData'));
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

        $admins = User::where('role', 'admin')->get();
        Notification::send($admins, new TicketNotification($ticket, 'Tiket Baru: ' . $ticket->title . ' dari ' . Auth::user()->name));

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

        $technician = User::find($request->technician_id);
        if ($technician) {
            $technician->notify(new TicketNotification($ticket, 'TUGAS BARU: ' . $ticket->title . ' menunggumu!'));
        }

        return back()->with('success', 'Teknisi berhasil ditugaskan!');
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        abort_if(Auth::user()->role !== 'technician' || Auth::id() !== $ticket->technician_id, 403, 'Akses Ditolak!');

        $request->validate([
            'status' => 'required',
            'note' => 'nullable|string',
            'image_after' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $imagePath = $ticket->image_after;
        if ($request->hasFile('image_after')) {
            $imagePath = $request->file('image_after')->store('tickets/after', 'public');
        }

        // Siapkan data untuk diupdate
        $updateData = [
            'status' => $request->status,
            'image_after' => $imagePath,
        ];

        // 👇 LOGIKA COMPLETED AT 👇
        if ($request->status == 'resolved') {
            if (is_null($ticket->completed_at)) {
                $updateData['completed_at'] = now(); // Catat waktu selesai
            }
        } else {
            $updateData['completed_at'] = null; // Reset jika teknisi mengubah status kembali ke on_progress
        }

        $ticket->update($updateData);

        TicketHistory::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'status' => $request->status,
            'note' => $request->note ?? 'Status tiket diperbarui menjadi ' . $request->status,
        ]);

        $admins = User::where('role', 'admin')->get();
        $reporter = User::find($ticket->reporter_id);

        if ($request->status == 'resolved') {
            Notification::send($admins, new \App\Notifications\TicketNotification($ticket, 'MENUNGGU VERIFIKASI: Teknisi telah menyelesaikan tiket ' . $ticket->ticket_number));
            if ($reporter) {
                $reporter->notify(new \App\Notifications\TicketNotification($ticket, 'INFO: Tiketmu (' . $ticket->ticket_number . ') telah selesai diperbaiki. Menunggu ACC Admin.'));
            }
        } else {
            $pesan = 'UPDATE: Status tiket ' . $ticket->ticket_number . ' kini menjadi ' . strtoupper($request->status);
            Notification::send($admins, new \App\Notifications\TicketNotification($ticket, $pesan));
            if ($reporter) {
                $reporter->notify(new \App\Notifications\TicketNotification($ticket, $pesan));
            }
        }

        return back()->with('success', 'Status pengerjaan berhasil diupdate!');
    }

    public function close(Request $request, Ticket $ticket)
    {
        abort_if(Auth::user()->role !== 'admin', 403, 'Akses Ditolak: Hanya Admin yang dapat menutup tiket!');
        $request->validate([
            'note' => 'nullable|string'
        ]);

        // Siapkan data
        $updateData = ['status' => 'closed'];

        // Jika tiket ditutup langsung tanpa lewat status resolved, kita tetap butuh waktu selesainya
        if (is_null($ticket->completed_at)) {
            $updateData['completed_at'] = now();
        }

        $ticket->update($updateData);

        TicketHistory::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'status' => 'closed',
            'note' => $request->note ?? 'Tiket telah diverifikasi dan ditutup oleh Admin.',
        ]);

        $reporter = User::find($ticket->reporter_id);
        if ($reporter) {
            $reporter->notify(new TicketNotification($ticket, 'SELESAI: Laporan ' . $ticket->ticket_number . ' telah ditutup. Terima kasih!'));
        }

        $technician = User::find($ticket->technician_id);
        if ($technician) {
            $technician->notify(new TicketNotification($ticket, 'DISETUJUI: Pekerjaanmu pada tiket ' . $ticket->ticket_number . ' telah di-ACC Admin.'));
        }

        return back()->with('success', 'Tiket berhasil ditutup secara permanen!');
    }

    public function reject(Request $request, Ticket $ticket)
    {
        abort_if(Auth::user()->role !== 'admin', 403, 'Akses Ditolak: Hanya Admin yang dapat menolak tiket!');

        $request->validate([
            'note' => 'required|string'
        ]);

        // 👇 LOGIKA RESET COMPLETED_AT 👇
        $ticket->update([
            'status' => 'on_progress',
            'completed_at' => null // Hapus waktu selesai karena dikembalikan ke teknisi!
        ]);

        TicketHistory::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'status' => 'on_progress',
            'note' => 'REVISI ADMIN: ' . $request->note,
        ]);

        $technician = User::find($ticket->technician_id);
        if ($technician) {
            $technician->notify(new TicketNotification($ticket, 'REVISI: Laporan ' . $ticket->ticket_number . ' dikembalikan oleh Admin.'));
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

        // LENGKAPI LOGIKA FILTER UNTUK EXPORT JUGA
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
        // TAMBAHAN: Filter Jenis Layanan untuk Ekspor
        if ($request->filled('service_type')) {
            $query->where('service_type', $request->service_type);
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

    public function readNotification(string $id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $notification = $user->notifications()->findOrFail($id);
        $notification->markAsRead();
        return redirect($notification->data['url']);
    }
}
