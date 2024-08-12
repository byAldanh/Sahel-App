<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CardInfoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
           'bank_name' => 'required|string|max:255',
            'iban' => 'required|string|max:34|unique:card_infos',
            'card_number' => 'required|digits:16|unique:card_infos',
            'name_on_card' => 'required|string|max:255',
            'expiry_date' => 'required|date',
            'type_of_card' => 'required|string|max:255',
        ];
    }
}
