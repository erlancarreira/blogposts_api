<?php

namespace App\Http\Requests;

use App\Traits\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
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
            'nome'  => 'sometimes|string|max:255',
            'email' => [
                'sometimes',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->route('user'))
            ],
            'password' => 'sometimes|string|min:6',
            'telefone' => 'sometimes|string|max:20', 
            'is_valid' => 'sometimes|boolean',
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
            'nome.string'     => 'O nome deve ser um texto',
            'nome.max'        => 'O nome não pode ter mais que 255 caracteres',
            'email.string'    => 'O email deve ser um texto',
            'email.email'     => 'O email deve ser um endereço de email válido',
            'email.max'       => 'O email não pode ter mais que 255 caracteres',
            'email.unique'    => 'Este email já está em uso',
            'password.string' => 'A senha deve ser um texto',
            'password.min'    => 'A senha deve ter no mínimo 6 caracteres',
            'telefone.string' => 'O telefone deve ser um texto',
            'telefone.max'    => 'O telefone não pode ter mais que 20 caracteres',
        ];
    }
}
