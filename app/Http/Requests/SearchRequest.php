<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SearchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->mergeIfMissing(['systemType' => $this->route('systemType')]);
        $this->mergeIfMissing(['searchValue' => $this->query('search')]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'systemType' => ['required', Rule::in(['kodeks','techexpert'])],
            'searchValue' => ['required', 'string', 'max:19']
        ];
    }
}
