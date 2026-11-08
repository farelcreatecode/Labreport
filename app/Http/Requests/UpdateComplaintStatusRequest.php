<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateComplaintStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['admin', 'teknisi'], true);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status'        => ['required', 'in:pending,diproses,selesai,ditolak'],
            'tanggapan'     => ['nullable', 'string', 'max:5000'],
            'technician_id' => [
                'nullable',
                'integer',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $tech = User::find($value);
                        if (! $tech || $tech->role !== 'teknisi') {
                            $fail('User yang dipilih harus memiliki role sebagai teknisi.');
                        }
                    }
                },
            ],
        ];
    }

    /**
     * Custom validation messages in Indonesian.
     */
    public function messages(): array
    {
        return [
            'status.required' => 'Status laporan wajib dipilih.',
            'status.in'       => 'Pilihan status tidak valid.',
            'tanggapan.max'   => 'Tanggapan maksimal 5000 karakter.',
        ];
    }
}
