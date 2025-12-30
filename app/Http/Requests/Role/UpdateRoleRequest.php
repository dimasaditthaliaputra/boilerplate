<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
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
        $role = $this->route('manajemen_role');

        return [
            'name' => 'required|unique:roles,name,' . $role,
            'permission_name' => 'nullable|array',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama Role harus diisi.',
            'name.unique' => 'Nama Role sudah digunakan.',
        ];
    }
}
