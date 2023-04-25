<?php

namespace Insomnicles\Laraexpress\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Config;
use Insomnicles\Laraexpress\ExpressableModel;
use Insomnicles\Laraexpress\ExpressionType;

class StoreExpressionRequest extends FormRequest
{
    /**
     * Indicates if the validator should stop on the first rule failure.
     *
     * @var bool
     */
    protected $stopOnFirstFailure = true;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'expressable_type'  => ['required', 'string'],
            'expressable_id'    => ['required', 'integer'],
            'expression_type_id'=> ['required', 'integer'],
            'expression'        => ['required', 'numeric'],
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
            'expressable_type.required'  => 'expressable_type is required',
            'expressable_type.string'    => 'expressable_type must be a string',
            'expressable_id.required'    => 'expressable_id is required',
            'expressable_id.integer'     => 'expressable_id must be an integer',
            'expression_type_id.required'=> 'expression_type_id is required',
            'expression_type_id.integer' => 'expression_type_id must be an integer',
            'expression.required'        => 'expression is required',
            'expression.numeric'         => 'expression must be numeric',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param \Illuminate\Validation\Validator $validator
     *
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(
            function ($validator) {
                $data = $validator->getData();
                $bag = $validator->getMessageBag();

                if (!array_key_exists('expressable_type', $data) || !array_key_exists('expressable_id', $data) || !array_key_exists('expression_type_id', $data) || !array_key_exists('expression', $data)) {
                    return;
                }

                if (!class_exists($data['expressable_type'])) {
                    $validator->errors()->add('Invalid Parameter', 'Expressable model class not found: '.$data['expressable_type']);

                    return;
                }

                // Check: Authorization: is the model (expressable_type) one that users are authorized to express to?
                $expressableModel = ExpressableModel::where('expressable_type', $data['expressable_type'])->where('expression_type_id', $data['expression_type_id'])->first();
                if (is_null($expressableModel)) {
                    $validator->errors()->add('Invalid Parameter', 'Expressable model not found: incorrect expressable_type or expression_type_id');

                    return;
                }

                // Check: there is an expressable_id (of expression_type) in DB
                // TODO: If microservice, skip validation; see design comments in README
                //if (!Config::get('microservice')) {
                $expressableObject = $data['expressable_type']::where('id', $data['expressable_id'])->first();
                if (is_null($expressableObject)) {
                    $validator->errors()->add('Invalid Parameter', 'Expressable object not found: incorrect expressable_type or expressable_id');

                    return;
                }
                //}

                // Check: expression is the correct value: correct data type and in value range
                //      E.g. Invalid input: Expression=5 but Expressable (App\Models\Image) ExpressionType is Boolean Like/Dislike
                //      E.g. Valid input:   Expression=true, Expressable (App\Models\Image), ExpressionType is Boolean Like/Dislike
                // expressable model with expressable_id exists in db
                // check whether expression value is te correct expression type for expressable

                // Check: is expression value the correct value type: integer or float (as required by expressionType)
                $expressionType = ExpressionType::where('id', '=', $expressableModel->expression_type_id)->first();
                $expressionIsInt = is_numeric($data['expression']) && strpos($data['expression'], '.') == false;
                if ($expressionType->isRangeInt() && !$expressionIsInt) {
                    $validator->errors()->add('Invalid Parameter', 'Invalid expression value: should be integer');

                    return;
                }

                // Check: is expression value in the expressionType range
                if (!($data['expression'] >= $expressionType->min && $data['expression'] <= $expressionType->max)) {
                    $validator->errors()->add('Invalid Parameter', 'Invalid expression value: should be within min-max range');

                    return;
                }
            }
        );
    }
}
