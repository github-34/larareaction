<?php

namespace App\Express\Requests;

use App\Express\Models\ExpressableModel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Config;

class ShowExpressionStatsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'expressable_type' => ['required', 'string'],
            'expressable_id'  => ['required', 'integer'],
            'expression_type_id'  => ['required', 'integer'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'expressable_type.required' => 'expressable_type is required',
            'expressable_type.string' => 'expressable_type must be a string',
            'expressable_id.required' => 'expressable_id is required',
            'expressable_id.integer' => 'expressable_id must be an integer',
            'expression_type_id.required' => 'expression_type_id is required',
            'expression_type_id.integer' => 'expression_type_id must be an integer',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $data = $validator->getData();
            $bag = $validator->getMessageBag();

            if (array_key_exists('expressable_type', $data)) {

                // Is the model (expressable_type) one that users can express to?
                //      E.g. Valid Expressable: App\Models\Images
                //      E.g. Invalid Expressable: App\Models\BalanceSheet: model not designed to have expressions

                $expressableModel = ExpressableModel::where('expressable_type', $data['expressable_type'])->first();

                if (is_null($expressableModel)) {
                    $validator->errors()->add('expressable_type', 'Invalid expressable type');
                    return;
                }

                // Does the expressable_id (of expression_type) exists?
                //      E.g. Invalid input:
                //      E.g. Valid input:
                // Note: If microservice, no validation here; see design comments in config
                // expressable model exists: check whether expressable id exists in database
                if (array_key_exists('expressable_id', $data) && !Config::get('microservice')) {
                    $expressableObject = $data['expressable_type']::where('id', $data['expressable_id'])->first();

                    if (is_null($expressableObject)) {
                        $validator->errors()->add('expressable_id', 'Invalid expressable_id');
                        return;
                    }
                }
            }
        });
    }
}
