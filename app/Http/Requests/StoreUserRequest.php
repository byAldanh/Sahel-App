<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; //always true , if it false it will cause a problems 
    } //the end of the method 

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */

     // Validation for users tables 
     // it validate the input from the user 
    public function rules(): array
    {
    //    return [ // by default all of them required 
    //         'name_user'=>['required','max:100'],
    //         'phone_user'=>['required','max:10'],
    //         'email_user'=> ['required','email','unique:users,email_user'],
    //         'password_user'=>['required','min:8'],
    //         'otp_generated'=>['optional'],
    //         'otp_entered'=>['optional'],
    //         'otp_expires_at'=>['optional'],
    //         'account_status'=>['optional'], //it can be a comment 
    //         'user_type_id'=>['required','in:1,2,3,4'], // range of numbers 
    //     ];//the end of the array bracket

        /*  'account_status'=>['optional'], 
        cause of this will be entered by the system not the user, and updated by
        the superadmin if the user market/collector 
        */
    } // the end of the method 
}// the end of the class 