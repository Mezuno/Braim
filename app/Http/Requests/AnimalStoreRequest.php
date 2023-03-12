<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Database\Query\Builder;

class AnimalStoreRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'animalTypes' => [
                'required',
                'array',
                Rule::exists('animal_types', 'id'),
            ],
//            'animalTypes.*' => [
//                'distinct',
//            ],
            'weight' => 'required|numeric',
            'length' => 'required|numeric',
            'height' => 'required|numeric',
            'gender' => [
                'required',
                'string',
                Rule::in(['MALE', 'FEMALE', 'OTHER']),
            ],
            'chipperId' => [
                'required',
                'numeric',
                Rule::exists('users', 'id'),
            ],
            'chippingLocationId' => [
                'required',
                'numeric',
                Rule::exists('locations', 'id'),
            ],
        ];
    }

    protected function failedValidation(Validator $validator) {
        $messages = $validator->errors()->messages();
        if (array_key_exists('animalTypes', $validator->errors()->messages()) or
            array_key_exists('chipperId', $validator->errors()->messages()) or
            array_key_exists('chippingLocationId', $validator->errors()->messages())
        ) {
            foreach ($messages as $message) {
                foreach ($message as $mess) {
                    if ($mess == "The selected chipper id is invalid." or
                        $mess == "The selected chipping location id is invalid." or
                        $mess == "The selected animal types is invalid.") {
                            throw new HttpResponseException(response()->json($validator->errors(), 404));
                    }
                }
                throw new HttpResponseException(response()->json($validator->errors(), 400));
            }
        }
        throw new HttpResponseException(response()->json($validator->errors(), 400));
    }
}
