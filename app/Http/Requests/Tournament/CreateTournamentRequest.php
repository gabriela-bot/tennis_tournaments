<?php

namespace App\Http\Requests\Tournament;

use App\Enum\Category;
use App\Enum\Status;
use App\Rules\checkPowerOfTwo;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'category' => [ 'nullable', Rule::in(Category::values())],
            'name'=> [ 'nullable', 'string'],
            'date'=> [ 'nullable','date', 'date_format:Y-m-d', 'after:start_date'],
            'players'=> [ 'nullable','integer', new checkPowerOfTwo(), 'max:32'],
            'status'=> [ 'nullable', Rule::in(Status::values())],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            "date" => $this->date ?? now()->format("Y-m-d"),
        ]);
    }
}
