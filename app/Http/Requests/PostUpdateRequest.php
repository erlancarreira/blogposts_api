<?php

namespace App\Http\Requests;

use App\Traits\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class PostUpdateRequest extends FormRequest
{
    use ApiRequest;
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
            'title'   => 'sometimes|string|max:255',
            'author'  => 'sometimes|exists:users,id',
            'content' => 'sometimes|string',
            'tags'    => 'sometimes|array',
            'tags.*'  => 'string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.string'   => 'O título deve ser um texto',
            'title.max'      => 'O título não pode ter mais que 255 caracteres',
            'author.exists'  => 'O autor informado não existe',
            'content.string' => 'O conteúdo deve ser um texto',
            'tags.array'     => 'As tags devem ser uma lista',
            'tags.*.string'  => 'Cada tag deve ser um texto'
        ];
    }
}
