<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Api\V1\BaseUserRequest;

class ReplaceUserRequest extends BaseUserRequest
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
        $rules = [

            'data.attributes.name' => 'required|string|max:255',
            'data.attributes.email' => 'required|email|max:255',
            'data.attributes.isManager' => 'required|boolean',
            'data.attributes.password' => 'required|string|max:255',
        ];



        return $rules;
    }

    public function messages()
    {
        return [
            'data.attributes.isManager.boolean' => 'The boolean option in invalid. Please 0, 1.',
        ];

    }
}
