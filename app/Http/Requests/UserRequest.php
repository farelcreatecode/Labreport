<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        $userId = $this->route('user') ? $this->route('user')->id : null;

        return [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'nim_nip'  => ['nullable', 'string', 'max:50', Rule::unique('users', 'nim_nip')->ignore($userId)],
            'role'     => ['required', 'in:mahasiswa,dosen,teknisi,admin'],
            'password' => [$this->isMethod('POST') ? 'required' : 'nullable', 'string', 'min:6'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'     => 'Nama pengguna wajib diisi.',
            'email.required'    => 'Email wajib diisi.',
            'email.unique'      => 'Email sudah terdaftar.',
            'nim_nip.unique'    => 'NIM / NIP sudah terdaftar.',
            'role.required'     => 'Role wajib ditentukan.',
            'password.required' => 'Password wajib diisi untuk pengguna baru.',
            'password.min'      => 'Password minimal 6 karakter.',
        ];
    }
}
