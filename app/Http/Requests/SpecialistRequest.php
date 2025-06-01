<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SpecialistRequest extends FormRequest
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
        $id = $this->route('specialist');
        
        return [
            'name' => 'required|string|max:255|unique:specialists,name' . $id,
            'photo' => $this->isMethod('post') ? 'required|image|mimes:jpeg,png,jpg,gif|max:2048' : 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'about' => 'required|string|max:1000',
            'price' => 'required|numeric|min:0',
            

        ];
    }
}
