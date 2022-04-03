<?php

namespace App\Express\Requests;

use App\Express\Models\ExpressableModel;
use App\Express\Models\ExpressionType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Config;

class StoreExpressionRequest extends FormRequest
{

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
            'expressable_type.required' => 'expressable_type is required',
            'expressable_type.string' => 'expressable_type must be a string',
            'expressable_id.required' => 'expressable_id is required',
            'expressable_id.integer' => 'expressable_id must be an integer',
            'expression_type_id.required' => 'expression_type_id is required',
            'expression_type_id.integer' => 'expression_type_id must be an integer',
            'expression.required' => 'expression is required',
            'expression.numeric' => 'expression must be numeric',
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
        $validator->after(
            function ($validator) {
                $data = $validator->getData();
                $bag = $validator->getMessageBag();

                /**
                 *
                 *
                 *
                 * VALIDATE Expression_TYPE_ID
                 */

                if (array_key_exists('expressable_type', $data)) {

                    // Is the model (expressable_type) one that users can express to?
                    //      E.g. Valid Expressable: App\Models\Images
                    //      E.g. Invalid Expressable: App\Models\BalanceSheet: model not designed to have expressions

                    $expressableModel = ExpressableModel::where('expressable_type', $data['expressable_type'])
                        ->where('expression_type_id', $data['expression_type_id'])
                        ->first();

                    if (is_null($expressableModel)) {
                        $validator->errors()->add('expressable_type', 'Invalid expressable type or expression type');
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

                    // Is the Expression of the correct Expression Type?
                    //      E.g. Invalid input: Expression=5 but Expressable (App\Models\Image) ExpressionType is Boolean Like/Dislike
                    //      E.g. Valid input: Expression  =true, Expressable (App\Models\Image), ExpressionType is Boolean Like/Dislike
                    // expressable model with expressabl  e_id exists in db
                    // check whether expression value is te correct expression type for expressable
                    if (array_key_exists('expression', $data)) {
                        //$expressableModels = ExpressableModel::where('expressable_type','=',$data['expressable_type'])->first();
                        $expressableObject  = '   ';
                        $expressionType = ExpressionType::where('id', '=', $expressableModel->expression_type_id)->first();

                        // expression is float                    if (is_numeric($data['expression    ']) && strpos($data['expression'], '.') !== false)
                        if ($expressionType->isRangeInt()) {
                            $validator->errors()->add('expressable_id', 'Invalid expression value: should be integer');
                            return;
                        }

                        // check if expression is n range                    if (!($data['expression '] >= $expressionType->min && $data['expression    '] <= $expressionType->max))
                        {
                            $validator->errors()->add('expressable_id', 'Invalid expression value: should be within min/max range');
                            return;
                        }
                    }
                }
            }
        );
    }
}
