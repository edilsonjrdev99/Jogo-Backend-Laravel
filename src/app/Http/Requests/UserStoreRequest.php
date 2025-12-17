<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

use App\Enums\UserClassEnum;

class UserStoreRequest extends FormRequest
{
    public function authorize(): bool {
        // Se tentar definir is_admin como true, deve ser admin autenticado
        if($this->has('is_admin') && $this->input('is_admin') === true) {
            return auth()->check() && auth()->user()->isAdmin();
        }

        return true;
    }

    public function rules(): array {
        return [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'is_admin' => ['sometimes', 'boolean'],
            'class'    => ['required', 'string', new Enum(UserClassEnum::class)]
        ];
    }

    public function messages(): array {
        return [
            'name.required'      => 'O nome é obrigatório.',
            'name.max'           => 'O nome não pode ter mais de 255 caracteres.',
            'email.required'     => 'O e-mail é obrigatório.',
            'email.email'        => 'O e-mail deve ser válido.',
            'email.unique'       => 'Este e-mail já está em uso.',
            'password.required'  => 'A senha é obrigatória.',
            'password.min'       => 'A senha deve ter pelo menos 8 caracteres.',
            'password.confirmed' => 'A confirmação da senha não corresponde.',
            'class.required'     => 'A classe é obrigatória.'
        ];
    }
}
