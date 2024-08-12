<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'product_name'=>'required|max:80',
            'product_image'=>'required',
            'price'=>'required|numeric',
            'quantity'=>'required|integer',
            'barcode_image'=>'required',
            'category_id'=>'required|exists:categories,id' // if the value exist in the categories table (id column)
        ];// the end of the validation 
    }
}
