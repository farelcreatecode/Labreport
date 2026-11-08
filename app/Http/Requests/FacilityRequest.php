<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FacilityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['admin', 'teknisi'], true);
    }

    public function rules(): array
    {
        return [
            'lab_id'         => ['required', 'integer', 'exists:labs,id'],
            'category_id'    => ['required', 'integer', 'exists:categories,id'],
            'nama_fasilitas' => ['required', 'string', 'max:255'],
            'kondisi'        => ['required', 'in:baik,rusak'],
        ];
    }

    public function messages(): array
    {
        return [
            'lab_id.required'         => 'Laboratorium wajib dipilih.',
            'category_id.required'    => 'Kategori fasilitas wajib dipilih.',
            'nama_fasilitas.required' => 'Nama fasilitas wajib diisi.',
            'kondisi.required'        => 'Kondisi fasilitas wajib ditentukan (baik/rusak).',
        ];
    }
}
