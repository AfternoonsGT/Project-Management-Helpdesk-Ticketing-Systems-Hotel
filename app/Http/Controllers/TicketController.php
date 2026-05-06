<?php

namespace App\Http\Controllers;

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

        // 👇 1. TAMBAHAN: Mengambil semua data kategori untuk ditampilkan di dropdown
        $categories = Category::all();

        $query = \App\Models\Ticket::query();

        if ($role == 'technician') {
            $query->where('technician_id', Auth::id());
        } elseif ($role == 'staff') {
            $query->where('reporter_id', Auth::id());
        }

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

        // 👇 2. TAMBAHAN: Logika filter pencarian jika dropdown Kategori dipilih
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $tickets = $query->latest()->paginate(5)->withQueryString();

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

        // 👇 3. TAMBAHAN: Memasukkan variabel 'categories' ke dalam compact agar bisa dibaca oleh HTML
        return view('dashboard', compact('tickets', 'totalTickets', 'progressTickets', 'resolvedTickets', 'closedTickets', 'categories'));
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

        return back()->with('success', 'Tiket dikembalikan ke Teknisi untuk diperbaiki ulang!');
    }

    public function edit(Ticket $ticket)
    {
        abort_if(Auth::user()->role !== 'admin', 403, 'Akses Ditolak: Hanya Admin yang boleh mengedit tiket!');
        return view('tickets.edit', compact('ticket'));
    }

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
    // FITUR EXPORT PDF & EXCEL (DIUBAH KE VERSI JAVASCRIPT)
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

        // 👇 4. TAMBAHAN: Logika filter Kategori untuk Export PDF & Excel
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $tickets = $query->latest()->get();

        if ($request->type == 'pdf') {
            // Langsung memanggil tampilan Javascript tanpa membebani server
            return view('tickets.export-pdf', compact('tickets'));
        }

        if ($request->type == 'excel') {
            return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\TicketsExport($tickets), 'Laporan_Tiket_Hotel.xlsx');
        }

        return back();
    }
}
