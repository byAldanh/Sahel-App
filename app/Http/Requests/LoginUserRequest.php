<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; //always true , if it false it will cause a problems 
    }//the end of the authorize method 

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */

     // Validation for users tables 
    public function rules(): array
    {
       return [ // by default all of them required
            'email_user'=> ['required','email'],
            'password_user'=>['required','min:8'],
        ];//the end of the array bracket

        /*  'account_status'=>['optional'], 
            cause of this will be entered by the system not the user, and updated by
            the superadmin if the user market/collector 
             */
    } // the end of the method 
}// the end of the class 