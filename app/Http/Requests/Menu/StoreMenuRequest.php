<?php

namespace App\Http\Requests\Menu;

use Illuminate\Foundation\Http\FormRequest;

class StoreMenuRequest extends FormRequest
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
            'name' => 'required|unique:menu,name',
            'route' => 'required|unique:menu,route',
            'url' => 'required',
            'order' => 'required|integer',
            'is_active' => 'required',
            'parent_id' => 'nullable|exists:menu,id',
            'icon' => 'nullable|string|max:255'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama Menu harus diisi.',
            'name.unique' => 'Nama Menu sudah digunakan.',
            'route.required' => 'Route harus diisi.',
            'route.unique' => 'Route sudah digunakan.',
            'url.required' => 'URL harus diisi.',
            'order.required' => 'Order harus diisi.',
            'is_active.required' => 'Status harus diisi.',
        ];
    }
}
