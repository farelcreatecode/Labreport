<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LabRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'nama_lab'   => ['required', 'string', 'max:255'],
            'lokasi'     => ['required', 'string', 'max:255'],
            'kepala_lab' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_lab.required' => 'Nama laboratorium wajib diisi.',
            'lokasi.required'   => 'Lokasi laboratorium wajib diisi.',
        ];
    }
}
