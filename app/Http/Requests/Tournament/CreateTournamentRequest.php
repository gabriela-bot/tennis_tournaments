<?php

namespace App\Http\Requests\Tournament;

use App\Models\Tournament;
use App\Status;
use Illuminate\Foundation\Http\FormRequest;

class CreateTournamentRequest extends FormRequest
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
//            'category',
//            'name',
//            'date',
//            'players',
//            'status',
//            'team'
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            "date" => $this->date ?? now(),
        ]);
    }
}
