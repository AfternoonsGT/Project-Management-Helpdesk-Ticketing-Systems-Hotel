<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Ticket;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Menampilkan halaman pengaturan sistem
     */
    public function index()
    {
        // Mengambil semua data setting ke dalam array agar mudah dipanggil di blade
        $settings = Setting::pluck('value', 'key');
        
        return view('settings.index', compact('settings'));
    }

    /**
     * Menyimpan SEMUA pengaturan sistem secara dinamis
     */
    public function update(Request $request)
    {
        // Mengambil semua data input dari form, kecuali token keamanan Laravel
        $data = $request->except(['_token', '_method']);

        // Loop otomatis: Apa pun nama inputnya (wa_gateway, sla_high, dll), 
        // akan langsung tersimpan ke database.
        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value ?? '0'] // Jika checkbox dimatikan (null), simpan sebagai '0'
            );
        }

        return back()->with('success', 'Konfigurasi sistem berhasil disimpan!');
    }

    /**
     * Menghapus foto dari tiket yang sudah "closed" (Ditutup Permanen)
     */
    public function optimizeStorage()
    {
        $closedTickets = Ticket::where('status', 'closed')->get();
        $deletedCount = 0;

        foreach ($closedTickets as $ticket) {
            if ($ticket->image_before) {
                Storage::disk('public')->delete($ticket->image_before);
                $ticket->image_before = null;
                $deletedCount++;
            }
            if ($ticket->image_after) {
                Storage::disk('public')->delete($ticket->image_after);
                $ticket->image_after = null;
                $deletedCount++;
            }
            $ticket->save();
        }

        return back()->with('success', "Storage Optimizer berhasil! $deletedCount file foto lama telah dihapus.");
    }

    /**
     * Melakukan backup database SQL otomatis
     */
    public function backupDatabase()
    {
        $database = env('DB_DATABASE');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        
        $filename = 'Backup_Helpdesk_' . date('Y-m-d_H-i-s') . '.sql';
        $path = storage_path('app/public/' . $filename);

        if (empty($password)) {
            $command = "mysqldump --user={$username} --host=" . env('DB_HOST') . " {$database} > {$path}";
        } else {
            $command = "mysqldump --user={$username} --password={$password} --host=" . env('DB_HOST') . " {$database} > {$path}";
        }

        exec($command);

        if (file_exists($path)) {
            return response()->download($path)->deleteFileAfterSend(true);
        }

        return back()->withErrors(['backup' => 'Gagal membuat backup. Pastikan MySQL terdaftar di Environment PATH Windows Anda.']);
    }
}