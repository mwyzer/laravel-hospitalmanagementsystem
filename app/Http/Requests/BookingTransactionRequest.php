<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookingTransactionRequest extends FormRequest
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
            //
            'doctor_id' => 'required|exists:doctors,id',
            'started_at' => [
                'required|date',
                // 'required',
                // 'date',
                    function ($attribute, $value, $fail) {
                    $date = Carbon::parse($value)->startOfDay();
                    $min = now()->addDay()->startOfDay();
                    $max = now()->addDays(3)->startOfDay();

                    if   ($date->lessThan($min) || $date->greaterThan($max)) {
                       $fail('The ' . $attribute . ' must be between ' . $min->toDateString() . ' and ' . $max->toDateString() . '.');
                    }
                }
            ],

            'time_at' => [
                'required|date_format:H:i',
                Rule::in(['10:00', '11:00', '13:00',
                '14:00', '15:00', '16:00', '17:00'])
            ],

            'proof' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }
}
