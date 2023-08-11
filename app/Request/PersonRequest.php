<?php

declare(strict_types=1);

namespace App\Request;

use Hyperf\Validation\Request\FormRequest;

class PersonRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'apelido' => 'required|string|max:32|unique:person,apelido',
            'nome' => 'required|string|max:100',
            'nascimento' => 'required|date|date_format:Y-m-d',
            'stack' => 'nullable|array',
            'stack.*' => 'string|max:32'
        ];
    }
}
