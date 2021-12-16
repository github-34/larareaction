<?php

namespace App\Http\Requests;

use App\Models\ReactableModel;
use App\Models\ReactionType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Config;
use Illuminate\Validation\ValidationException;
use ReflectionClass;

class StoreReactionRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'reactable_type'=> [ 'required', 'string' ],
            'reactable_id'  => [ 'required', 'integer' ],
            'reaction'      => [ 'required', 'numeric' ],
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
            'reactable_type.required' => 'reactable_type is required',
            'reactable_type.string' => 'reactable_type must be a string',
            'reactable_id.required' => 'reactable_id is required',
            'reactable_id.integer' => 'reactable_id must be an integer',
            'reaction.required' => 'reaction is required',
            'reaction.numeric' => 'reaction must be numeric',
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


            if (array_key_exists('reactable_type', $data))
            {

                // Is the model (reactable_type) one that users can react to?
                //      E.g. Valid Reactable: App\Models\Images
                //      E.g. Invalid Reactable: App\Models\BalanceSheet: model not designed to have reactions

                $reactableModel = ReactableModel::where('reactable_type','=',$data['reactable_type'])->first();

                if (is_null($reactableModel))
                {
                    $validator->errors()->add('reactable_type', 'Invalid reactable type');
                    return;
                }

                // Does the reactable_id (of reaction_type) exists?
                //      E.g. Invalid input:
                //      E.g. Valid input:
                // Note: If microservice, no validation here; see design comments in config
                // reactable model exists: check whether reactable id exists in database
                if (array_key_exists('reactable_id', $data) && !Config::get('microservice'))
                {
                    $reactableObject = $data['reactable_type'] :: where('id', $data['reactable_id'] )->first();

                    if (is_null($reactableObject))
                    {
                        $validator->errors()->add('reactable_id', 'Invalid reactable_id');
                        return;
                    }
                }

                // Is the Reaction of the correct Reaction Type?
                //      E.g. Invalid input: Reaction=5 but Reactable (App\Models\Image) ReactionType is Boolean Like/Dislike
                //      E.g. Valid input: Reaction=true, Reactable (App\Models\Image), ReactionType is Boolean Like/Dislike
                // reactable model with reactable_id exists in db
                // check whether reaction value is the correct reaction type for reactable
                if (array_key_exists('reaction', $data))
                {
                    //$reactableModels = ReactableModel::where('reactable_type','=',$data['reactable_type'])->first();
                    $reactableObject  = '';
                    $reactionType = ReactionType::where('id','=',$reactableModel->reaction_type_id)->first();
                    // reaction is float
                    if (is_numeric($data['reaction']) && strpos($data['reaction'], '.') !== false)
                        if ($reactionType->isRangeInt())
                        {
                            $validator->errors()->add('reactable_id', 'Invalid reaction value: should be integer');
                            return;
                        }

                    // check if reaction is in range
                    if (!($data['reaction'] >= $reactionType->min && $data['reaction'] <= $reactionType->max))
                    {
                        $validator->errors()->add('reactable_id', 'Invalid reaction value: should be within min/max range');
                        return;
                    }
                }
            }
        }
    );
   }
}
