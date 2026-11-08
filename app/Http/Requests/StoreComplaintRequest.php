<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreComplaintRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['mahasiswa', 'dosen'], true);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'lab_id'            => ['required', 'integer', 'exists:labs,id'],
            'category_id'       => ['required', 'integer', 'exists:categories,id'],
            'nomor_pc'          => ['nullable', 'string', 'max:50'],
            'deskripsi_masalah' => ['required', 'string', 'min:5', 'max:5000'],
            'foto_bukti'        => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'], // Max 2MB
        ];
    }

    /**
     * Custom validation messages in Indonesian.
     */
    public function messages(): array
    {
        return [
            'lab_id.required'            => 'Laboratorium wajib dipilih.',
            'lab_id.exists'              => 'Laboratorium yang dipilih tidak valid.',
            'category_id.required'       => 'Jenis fasilitas / kategori wajib dipilih.',
            'category_id.exists'         => 'Kategori yang dipilih tidak valid.',
            'nomor_pc.max'               => 'Nomor PC maksimal 50 karakter.',
            'deskripsi_masalah.required' => 'Deskripsi masalah kerusakan wajib diisi.',
            'deskripsi_masalah.min'      => 'Deskripsi masalah minimal berisi 5 karakter.',
            'foto_bukti.image'           => 'File bukti harus berupa gambar.',
            'foto_bukti.mimes'           => 'Format foto bukti yang diperbolehkan adalah JPG, JPEG, atau PNG.',
            'foto_bukti.max'             => 'Ukuran foto bukti tidak boleh melebihi 2MB.',
        ];
    }
}
