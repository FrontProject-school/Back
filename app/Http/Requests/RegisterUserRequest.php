<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
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
            "stuId" => "required|string|size:7",
            "email" => "required|string|max:40",
            "password" => "required|min:8",
            "grade" => "required",
            "depart" => "required",
            "name" => "required",
            "phone" => "required|string|size:11",
            "gender"=> "required|string|size:1",
            "birth"=> "required",
        ];
    }
}
