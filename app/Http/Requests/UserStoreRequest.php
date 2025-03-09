<?php

namespace App\Http\Requests;

use App\Traits\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
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
            'nome'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'telefone' => 'required|string|max:20', 
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required'     => 'O nome é obrigatório',
            'email.required'    => 'O email é obrigatório',
            'email.email'       => 'O email deve ser um endereço de email válido',
            'email.unique'      => 'Este email já está em uso',
            'password.required' => 'A senha é obrigatória',
            'password.min'      => 'A senha deve ter pelo menos 6 caracteres',
            'telefone.required' => 'O telefone é obrigatório',
        ];
    }
}
