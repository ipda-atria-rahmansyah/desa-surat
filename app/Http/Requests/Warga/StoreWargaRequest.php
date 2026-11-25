<?php

namespace App\Http\Requests\Warga;

use Illuminate\Foundation\Http\FormRequest;

class StoreWargaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'nik' => preg_replace('/[\s-]+/', '', (string) $this->input('nik', '')),
            'nama_lengkap' => trim((string) $this->input('nama_lengkap', '')),
            'alamat' => trim((string) $this->input('alamat', '')),
        ]);
    }

    public function rules(): array
    {
        return [
            'nik' => ['required', 'string', 'digits:16', 'unique:tbmaster_warga,nik'],
            'nama_lengkap' => ['required', 'string', 'min:3', 'max:100', "regex:/^[\pL\s\.\,\'\-]+$/u"],
            'alamat' => ['required', 'string', 'max:255'],
        ];
    }

    public function attributes(): array
    {
        return [
            'nik' => 'NIK',
            'nama_lengkap' => 'Nama Lengkap',
            'alamat' => 'Alamat',
        ];
    }

    public function messages(): array
    {
        return [
            'nik.required' => ':attribute wajib diisi.',
            'nik.digits' => ':attribute harus terdiri dari :digits angka.',
            'nik.unique' => ':attribute sudah terdaftar.',
            'nama_lengkap.required' => ':attribute wajib diisi.',
            'nama_lengkap.min' => ':attribute minimal :min karakter.',
            'nama_lengkap.max' => ':attribute maksimal :max karakter.',
            'nama_lengkap.regex' => ':attribute tidak valid.',
            'alamat.required' => ':attribute wajib diisi.',
            'alamat.max' => ':attribute maksimal :max karakter.',
        ];
    }
}
