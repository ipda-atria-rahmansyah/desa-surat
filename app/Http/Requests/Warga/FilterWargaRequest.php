<?php

namespace App\Http\Requests\Warga;

use Illuminate\Foundation\Http\FormRequest;

class FilterWargaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['q' => trim((string) $this->input('q', ''))]);
    }

    public function rules(): array
    {
        return ['q' => ['nullable', 'string', 'max:100']];
    }

    public function attributes(): array
    {
        return ['q' => 'pencarian'];
    }

    public function messages(): array
    {
        return ['q.max' => 'Pencarian maksimal 100 karakter.'];
    }
}
