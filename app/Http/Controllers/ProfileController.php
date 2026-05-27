<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage; // <--- WAJIB IMPORT BAGIAN INI
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman formulir profil pengguna.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Memperbarui informasi profil pengguna (Termasuk Nama, Email, WhatsApp, dan Avatar).
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();

        // 👇 LOGIKA PROSES UPLOAD FOTO PROFIL BARU 👇
        if ($request->hasFile('avatar')) {
            // Jika user sebelumnya sudah punya foto, hapus foto lamanya biar hemat storage
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            
            // Simpan file baru ke dalam folder 'avatars' di dalam disk public
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->fill($data);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Menghapus akun pengguna dari sistem.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Hapus foto profil dari storage server saat akun dihapus permanen
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}