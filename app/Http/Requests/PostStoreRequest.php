<?php

namespace App\Http\Requests;

use App\Traits\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class PostStoreRequest extends FormRequest
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
            'title'   => 'required|string|max:255',
            'author'  => 'required|exists:users,id',
            'content' => 'required|string',
            'tags'    => 'required|array',
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
            'title.required'   => 'O título é obrigatório',
            'title.string'     => 'O título deve ser um texto',
            'title.max'        => 'O título não pode ter mais que 255 caracteres',
            'author.required'  => 'O autor é obrigatório',
            'author.exists'    => 'O autor informado não existe',
            'content.required' => 'O conteúdo é obrigatório',
            'content.string'   => 'O conteúdo deve ser um texto',
            'tags.required'    => 'As tags são obrigatórias',
            'tags.array'       => 'As tags devem ser uma lista',
            'tags.*.string'    => 'Cada tag deve ser um texto'
        ];
    }
}
