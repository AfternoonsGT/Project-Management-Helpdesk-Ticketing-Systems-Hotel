<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna diizinkan membuat permintaan ini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Dapatkan aturan validasi yang berlaku untuk permintaan ini.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ], // <--- Array email ditutup rapi di sini
            
            // 👇 SEHARUSNYA DI SINI (Sejajar dengan name dan email, berdiri sendiri) 👇
            'phone' => ['nullable', 'string', 'max:20'], 
            // 👇 VALIDASI UNTUK FOTO PROFIL (MAKSIMAL 2MB) 👇
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ];
    }
}