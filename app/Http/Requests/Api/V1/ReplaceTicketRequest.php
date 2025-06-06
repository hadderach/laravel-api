<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Api\V1\BaseTicketRequest;

class ReplaceTicketRequest extends BaseTicketRequest
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

            'data.attributes.title' => 'required|string|max:255',
            'data.attributes.description' => 'required|string|max:255',
            'data.attributes.status' => 'required|string|in:A,C,H,X',
            'data.relationships.author.data.id' => 'required|integer',
        ];

        if ($this->routeIs('tickets.store')) {

            $rules[] = 'required|integer';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'data.attributes.status.in' => 'The status option in invalid. Please A, C, H, X.',
        ];

    }
}
