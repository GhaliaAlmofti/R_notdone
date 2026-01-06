<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSchoolRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'school_admin';
    }

    public function rules(): array
    {
        return [
            'name'           => ['required', 'string', 'max:255'],
            'email'          => ['nullable', 'email', 'max:255'],
            'phone'          => ['nullable', 'string', 'max:20'],
            'address'        => ['nullable', 'string', 'max:255'],

            'area'           => ['required', 'string', 'max:255'],
            'category'       => ['required', 'string', 'max:255'],
            'level'          => ['required', 'string', 'max:255'],

            'president_name' => ['nullable', 'string', 'max:255'],
            'fees_range'     => ['nullable', 'string', 'max:255'],
            'gender_type'    => ['required', 'in:boys,girls,mixed'],
            'curriculum'     => ['nullable', 'string', 'max:255'],

            // logo upload
            'logo'           => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }
}
