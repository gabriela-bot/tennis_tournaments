<?php

namespace App\Http\Requests\Player;

use App\Enum\Category;
use App\Enum\Status;
use App\Rules\checkCategoryPlayers;
use App\Rules\checkPowerOfTwo;
use App\Rules\checkPowerOfTwoArray;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PlayPlayerRequest extends FormRequest
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
            'category' => [ 'required', Rule::in(Category::values())],
            'name'=> [ 'nullable', 'string'],
            'date'=> [ 'nullable','date', 'date_format:Y-m-d', 'after:start_date'],
            'players'=> [ 'required', 'array',  'min:2', 'max:64', new checkPowerOfTwoArray(), new checkCategoryPlayers() ],
            'players.*'=> [ 'required', 'integer' , 'exists:players,id'  ],
            'status'=> [ 'nullable', Rule::in(Status::values())],
            'team'=> [ 'nullable', 'integer', Rule::in([0,1])],
        ];
    }


    public function prepareForValidation()
    {
        $this->merge([
            "date" => $this->date ?? now()->format("Y-m-d"),
        ]);
    }
}
