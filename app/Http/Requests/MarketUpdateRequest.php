<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MarketUpdateRequest extends FormRequest
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
        // Validate the request data
        'email_user' => 'optional', 'email', // Ensure current email is required
        'password' => 'optional|min:8', // Current password
        'name_user' => 'optional|max:255',
        'phone_user' => 'optional|string|max:10', // Ensure it matches the database schema
        'new_email_user' => 'optional', 'email', 'unique:users,email_user', // Ensure new email is unique
        'new_password' => 'optional|min:8', // New password (optional)
        'market_logo'=>'optional',
        'commercial_regitser'=>'optional',
        'branch'=>'optional|max:20'
        ];
    }
}
