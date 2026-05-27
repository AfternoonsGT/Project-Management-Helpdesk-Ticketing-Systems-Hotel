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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TicketsExport;
use App\Models\Setting; // Pastikan Model Setting dipanggil

class TicketController extends Controller
{
    // =========================================================================
    // MESIN WHATSAPP OTOMATIS (TERINTEGRASI DENGAN HALAMAN SETTINGS)
    // =========================================================================
   // =========================================================================
    // MESIN WHATSAPP OTOMATIS (VERSI LOCALHOST / LARAGON FIX)
    // =========================================================================
    private function sendWhatsApp($targetNumber, $message)
    {
        if (empty($targetNumber)) return; 

        // 1. Baca pengaturan dari database
        $isWaEnabled = Setting::where('key', 'wa_gateway')->value('value');
        $fonnteToken = Setting::where('key', 'fonnte_token')->value('value');

        // 2. Katup Pengaman
        if ($isWaEnabled != '1' || empty($fonnteToken)) {
            \Log::info('WA Gagal: Sakelar mati atau Token kosong.'); // Catat ke log
            return; 
        }

        try {
            // 3. withoutVerifying() SANGAT PENTING untuk localhost/Laragon
            $response = Http::withoutVerifying()->withHeaders([
                'Authorization' => $fonnteToken
            ])->post('https://api.fonnte.com/send', [
                'target' => $targetNumber,
                'message' => $message,
                // 'countryCode' => '62', // Sengaja dimatikan agar format 08 atau 628 bisa masuk semua
            ]);

            // 4. Catat hasil jawaban dari server Fonnte ke file log
            \Log::info('Respons Fonnte API: ' . $response->body());
            
        } catch (\Exception $e) {
            \Log::error('Error WA (System Crash): ' . $e->getMessage());
        }
    }
    // =======================================================
    // =========================================================================

    public function index(Request $request)
    {
        $role = Auth::user()->role;
        $categories = Category::all();
        $query = Ticket::query();

        // Filter berdasarkan Role
        if ($role == 'technician') {
            $query->where('technician_id', Auth::id());
        } elseif ($role == 'staff') {
            $query->where('reporter_id', Auth::id());
        }

        // Filter Pencarian
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('ticket_number', 'like', '%' . $request->search . '%')
                    ->orWhere('location', 'like', '%' . $request->search . '%')
                    ->orWhere('floor', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('year')) $query->whereYear('created_at', $request->year);
        if ($request->filled('month')) $query->whereMonth('created_at', $request->month);
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('category_id')) $query->where('category_id', $request->category_id);
        if ($request->filled('floor')) $query->where('floor', $request->floor);
        if ($request->filled('service_type')) $query->where('service_type', $request->service_type);

        // Ambil Data
        $tickets = $query->latest()->paginate(5)->withQueryString();

       // =========================================================
        // SAKELAR CERDAS: PENGALIH HALAMAN JIKA ADA FILTER STATUS DARI SIDEBAR
        // =========================================================
        if ($request->filled('status') && $request->query('view') == 'clean') {
            return view('status', compact('tickets')); 
        }
        // =========================================================

        // Hitung Data untuk Kartu di Dashboard Utama
        if ($role == 'admin') {
            $totalTickets = Ticket::count();
            $progressTickets = Ticket::whereIn('status', ['assigned', 'on_progress'])->count();
            $resolvedTickets = Ticket::where('status', 'resolved')->count();
            $closedTickets = Ticket::where('status', 'closed')->count();
            $openTickets = Ticket::where('status', 'open')->count();
        } elseif ($role == 'technician') {
            $totalTickets = Ticket::where('technician_id', Auth::id())->count();
            $progressTickets = Ticket::where('technician_id', Auth::id())->whereIn('status', ['assigned', 'on_progress'])->count();
            $resolvedTickets = Ticket::where('technician_id', Auth::id())->where('status', 'resolved')->count();
            $closedTickets = Ticket::where('technician_id', Auth::id())->where('status', 'closed')->count();
            $openTickets = Ticket::where('technician_id', Auth::id())->where('status', 'open')->count();
        } else {
            $totalTickets = Ticket::where('reporter_id', Auth::id())->count();
            $progressTickets = Ticket::where('reporter_id', Auth::id())->whereIn('status', ['assigned', 'on_progress'])->count();
            $resolvedTickets = Ticket::where('reporter_id', Auth::id())->where('status', 'resolved')->count();
            $closedTickets = Ticket::where('reporter_id', Auth::id())->where('status', 'closed')->count();
            $openTickets = Ticket::where('reporter_id', Auth::id())->where('status', 'open')->count();
        }

        // Data untuk Grafik
        $semuaKategori = Category::all();
        $chartLabels = [];
        $chartData = [];
        foreach($semuaKategori as $kategori) {
            $chartLabels[] = $kategori->name;
            $chartData[] = Ticket::where('category_id', $kategori->id)->count();
        }

        $statusLabels = ['Open', 'On Progress', 'Resolved', 'Closed'];
        $statusData = [
            Ticket::where('status', 'open')->count(),
            Ticket::where('status', 'on_progress')->count(),
            Ticket::where('status', 'resolved')->count(),
            Ticket::where('status', 'closed')->count(),
        ];

        return view('dashboard', compact('tickets', 'openTickets', 'progressTickets', 'resolvedTickets', 'closedTickets', 'categories', 'chartLabels', 'chartData', 'statusLabels', 'statusData'));
    }

    public function create(Request $request)
    {
        $categories = Category::all();
        $lokasiOtomatis = $request->query('lokasi');
        return view('tickets.create', compact('categories', 'lokasiOtomatis'));
    }

    // ==========================================
    // 1. STAFF BUAT TIKET -> NOTIF KE WA ADMIN
    // ==========================================
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
        ], [
            'image_before.max'   => 'Maaf, ukuran foto maksimal adalah 2MB.',
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
        
        // WA KE ADMIN
        $namaKategori = Category::find($request->category_id)->name ?? '-';
        $pesanWaAdmin = "🚨 *LAPORAN KERUSAKAN BARU* 🚨\n\n"
                    . "Nomor Tiket: *" . $ticketNumber . "*\n"
                    . "Pelapor: " . Auth::user()->name . "\n"
                    . "Kategori: " . $namaKategori . "\n"
                    . "Lokasi: " . $request->location . " (" . $request->floor . ")\n"
                    . "Prioritas: *" . strtoupper($request->priority) . "*\n\n"
                    . "Kendala:\n_" . $request->title . "_\n\n"
                    . "Mohon segera login ke Helpdesk untuk menugaskan Teknisi.";
                    
        foreach ($admins as $admin) {
            $this->sendWhatsApp($admin->phone ?? '081234567890', $pesanWaAdmin);
        }

        return redirect()->route('dashboard')->with('success', 'Laporan kerusakan berhasil dikirim!');
    }

    public function show(Ticket $ticket)
    {
        $technicians = User::where('role', 'technician')->get();
        $histories = TicketHistory::where('ticket_id', $ticket->id)->latest()->get();
        return view('tickets.show', compact('ticket', 'technicians', 'histories'));
    }

    // ==========================================
    // 2. ADMIN TUGASKAN -> NOTIF KE WA TEKNISI
    // ==========================================
    public function assign(Request $request, Ticket $ticket)
    {
        abort_if(Auth::user()->role !== 'admin', 403, 'Akses Ditolak!');

        $request->validate(['technician_id' => 'required']);

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
            
            // WA KE TEKNISI
            $pesanWaTeknisi = "🛠️ *TUGAS PERBAIKAN BARU* 🛠️\n\n"
                            . "Halo " . $technician->name . ", kamu mendapat tugas baru!\n\n"
                            . "No. Tiket: *" . $ticket->ticket_number . "*\n"
                            . "Lokasi: " . $ticket->location . " (" . $ticket->floor . ")\n"
                            . "Kendala:\n_" . $ticket->title . "_\n\n"
                            . "Mohon segera dicek dan dikerjakan ya!";
            
            $this->sendWhatsApp($technician->phone, $pesanWaTeknisi);
        }

        return back()->with('success', 'Teknisi berhasil ditugaskan!');
    }

    // ==========================================
    // 3. TEKNISI SELESAI -> NOTIF KE WA ADMIN
    // ==========================================
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

        $updateData = [
            'status' => $request->status,
            'image_after' => $imagePath,
        ];

        if ($request->status == 'resolved') {
            if (is_null($ticket->completed_at)) {
                $updateData['completed_at'] = now();
            }
        } else {
            $updateData['completed_at'] = null;
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
            Notification::send($admins, new TicketNotification($ticket, 'MENUNGGU VERIFIKASI: Teknisi telah menyelesaikan tiket ' . $ticket->ticket_number));
            if ($reporter) {
                $reporter->notify(new TicketNotification($ticket, 'INFO: Tiketmu (' . $ticket->ticket_number . ') telah selesai diperbaiki. Menunggu ACC Admin.'));
            }

            // WA KE ADMIN
            $pesanWaSelesai = "✅ *TIKET SELESAI DIPERBAIKI* ✅\n\n"
                            . "Nomor Tiket: *" . $ticket->ticket_number . "*\n"
                            . "Teknisi: " . Auth::user()->name . "\n"
                            . "Catatan Teknisi: " . ($request->note ?? '-') . "\n\n"
                            . "Pekerjaan sudah selesai dilakukan. Mohon Admin segera login untuk memverifikasi dan menutup laporan (Close).";
                            
            foreach ($admins as $admin) {
                $this->sendWhatsApp($admin->phone ?? '081234567890', $pesanWaSelesai);
            }

        } else {
            $pesan = 'UPDATE: Status tiket ' . $ticket->ticket_number . ' kini menjadi ' . strtoupper($request->status);
            Notification::send($admins, new TicketNotification($ticket, $pesan));
            if ($reporter) {
                $reporter->notify(new TicketNotification($ticket, $pesan));
            }
        }

        return back()->with('success', 'Status pengerjaan berhasil diupdate!');
    }

    // ==========================================
    // 4. ADMIN TUTUP -> NOTIF KE WA STAFF/PELAPOR
    // ==========================================
    public function close(Request $request, Ticket $ticket)
    {
        abort_if(Auth::user()->role !== 'admin', 403, 'Akses Ditolak!');
        $request->validate(['note' => 'nullable|string']);

        $updateData = ['status' => 'closed'];
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
            
            // WA KE STAFF PELAPOR
            $pesanWaStaff = "🎉 *LAPORAN DITUTUP (SELESAI)* 🎉\n\n"
                          . "Halo " . $reporter->name . ",\n"
                          . "Laporan kerusakanmu (Tiket: *" . $ticket->ticket_number . "*) telah berhasil diperbaiki dan diverifikasi oleh tim IT/Engineering.\n\n"
                          . "Catatan Admin: " . ($request->note ?? '-') . "\n\n"
                          . "Terima kasih telah membantu menjaga fasilitas Hotel Pangeran! 🙏";
            
            $this->sendWhatsApp($reporter->phone, $pesanWaStaff);
        }

        $technician = User::find($ticket->technician_id);
        if ($technician) {
            $technician->notify(new TicketNotification($ticket, 'DISETUJUI: Pekerjaanmu pada tiket ' . $ticket->ticket_number . ' telah di-ACC Admin.'));
        }

        return back()->with('success', 'Tiket berhasil ditutup secara permanen!');
    }

    public function reject(Request $request, Ticket $ticket)
    {
        abort_if(Auth::user()->role !== 'admin', 403, 'Akses Ditolak!');

        $request->validate([
            'note' => 'required|string'
        ]);

        $ticket->update([
            'status' => 'on_progress',
            'completed_at' => null
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
        abort_if(Auth::user()->role !== 'admin', 403, 'Akses Ditolak!');
        $categories = Category::all();
        return view('tickets.edit', compact('ticket', 'categories'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        abort_if(Auth::user()->role !== 'admin', 403, 'Akses Ditolak!');
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
        abort_if(Auth::user()->role !== 'admin', 403, 'Akses Ditolak!');

        if ($ticket->image_before) {
            Storage::disk('public')->delete($ticket->image_before);
        }
        if ($ticket->image_after) {
            Storage::disk('public')->delete($ticket->image_after);
        }

        TicketHistory::where('ticket_id', $ticket->id)->delete();
        $ticket->delete();

        return redirect()->route('dashboard')->with('success', 'Tiket dan fotonya berhasil dihapus secara permanen!');
    }

    public function export(Request $request)
    {
        $role = Auth::user()->role;
        $query = Ticket::query();

        if ($role == 'technician') {
            $query->where('technician_id', Auth::id());
        } elseif ($role == 'staff') {
            $query->where('reporter_id', Auth::id());
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('ticket_number', 'like', '%' . $request->search . '%')
                    ->orWhere('location', 'like', '%' . $request->search . '%')
                    ->orWhere('floor', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('year')) $query->whereYear('created_at', $request->year);
        if ($request->filled('month')) $query->whereMonth('created_at', $request->month);
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('category_id')) $query->where('category_id', $request->category_id);
        if ($request->filled('floor')) $query->where('floor', $request->floor);
        if ($request->filled('service_type')) $query->where('service_type', $request->service_type);

        $tickets = $query->latest()->get();

        if ($request->type == 'pdf') {
            return view('tickets.export-pdf', compact('tickets'));
        }

        if ($request->type == 'excel') {
            return Excel::download(new TicketsExport($tickets), 'Laporan_Tiket_Hotel.xlsx');
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

    public function markAllRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    }
}