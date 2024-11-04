<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(['success' => false, 'message' => $validator->errors()], 412));
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'category_id' => 'required',
            'name' => 'required',
            'product_code' => 'required',
            'description' => 'required',
            'image' => 'required',
            'is_active' => 'required',
            'product_customizes' => 'required|array',
            'product_customizes.*.size' => 'required',
            'product_customizes.*.price' => 'required',
        ];
    }

    public function messages()
    {
        return[
            'product_customizes.*.size.required' => 'The size field is required.',
            'product_customizes.*.price.required' => 'The price field is required.',
        ];
    }

}
