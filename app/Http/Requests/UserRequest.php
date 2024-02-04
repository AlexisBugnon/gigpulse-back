<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        // 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
        return [
            'name'=> 'required|string|max:255',
            'email'=> 'required|email',
            'password' => 'required|string|min:8',
            'profile_picture'=> 'url',
            'role' => 'in:Super admin, Admin, User',
            'description' => 'required',
            'job' => 'required|string|max:255',
            'is_active' => 'boolean',
        ];
    }
}
