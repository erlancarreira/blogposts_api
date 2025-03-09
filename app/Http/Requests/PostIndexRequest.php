<?php

namespace App\Http\Requests;

use App\Traits\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class PostIndexRequest extends FormRequest
{
    use ApiRequest;
    
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tag'   => 'sometimes|string',
            'query' => 'sometimes|string',
        ];
    }

    public function messages(): array
    {
        return [
            'tag.string'   => 'A tag deve ser um texto',
            'query.string' => 'A busca deve ser um texto'
        ];
    }
}
