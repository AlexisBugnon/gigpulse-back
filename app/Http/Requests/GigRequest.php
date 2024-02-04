<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GigRequest extends FormRequest
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
        // Validation rule received in the requests when creating and updating a gig
        return [
            'title' => 'required|max:255',
            'slug' => 'max:255',
            'user_id' => 'required|exists:users,id',
            'picture' => 'required|url',
            'description' => 'required',
            // Numeric = float or integer
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'is_active' => 'required|boolean',
            'average_rating' => 'numeric',
            'tags' => 'array'
        ];
    }
}
