<?php

namespace App\Http\Requests\HakAkses;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHakAksesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'access' => 'required|array|min:1',
            'menu' => 'required|string|exists:menu,name'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama Hak Akses harus diisi.',
            'access.required' => 'Akses harus diisi.',
            'access.min' => 'Akses harus memiliki minimal 1 item.',
            'menu.required' => 'Menu harus diisi.',
            'menu.exists' => 'Menu tidak ditemukan.',
        ];
    }
}
