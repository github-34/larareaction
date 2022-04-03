<?php

namespace App\Express;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

use App\Express\Models\ExpressableModel;
use App\Express\Models\Expression;
use App\Express\Models\ExpressionType;

class ExpressionService
{

    public function obtain(int $id): Expression
    {
        return Expression::findOrFail($id);
    }

    public function storeOrUpdate(array $validatedInput): Expression
    {
        $expression = Expression::where([
            'expressable_type' => $validatedInput['expressable_type'],
            'expressable_id' => $validatedInput['expressable_id'],
            'expression_type_id' => $validatedInput['expression_type_id'],
            'user_id' => Auth::user()->id,
        ])->first();

        $inputExpression = $validatedInput['expression'];
        $expressionValue = (is_numeric($inputExpression) && strpos($inputExpression, ".") !== false) ? floatval($inputExpression) : intval($inputExpression);

        // Create new Expression
        if (is_null($expression))
            return Expression::create([
                'expressable_type' => $validatedInput['expressable_type'],
                'expressable_id' => $validatedInput['expressable_id'],
                'expression_type_id' => $validatedInput['expression_type_id'],
                'user_id' => Auth::user()->id,
                'expression' => $expressionValue,
                'created_from' => $this->getIP()
            ]);

        // Update Existing Expression
        $updatedExpression = $expression->fill([
            'expressable_type' => $validatedInput['expressable_type'],
            'expressable_id' => $validatedInput['expressable_id'],
            'expression_type_id' => $validatedInput['expression_type_id'],
            'user_id' => Auth::user()->id,
            'expression' => $expressionValue,
            'updated_from' => $this->getIP()
        ]);
        $updatedExpression->save();

        return $updatedExpression;
    }

    public function update(Expression $expression, array $validatedInput): Expression
    {
        return $expression->fill(array_merge($validatedInput, ['updated_from' => $this->getIP()]));
    }

    public function delete(Expression $expression): void
    {
        $expression->deleted_from = $this->getIP();
        $expression->save();
        $expression->delete();
    }

    public function obtainExpressionType($id)
    {
        return ExpressionType::findOrFail($id);
    }

    public function obtainExpressableModel(String $expressableType) : Object
    {
        return ExpressableModel::where('expressable_type', $expressableType)->get();
    }
    // assumption expressable objects are all of the same kind: i.e. images, or users,etc.
    public function obtainExpressableInfo(Collection $expressableObjects) : array
    {
        if ($expressableObjects->isEmpty())
            return [
                'images' => [],
                'stats' => [ 'count' => 0, 'avg' => 0]
            ];

        $expressableInfo = $expressionTypes = $stats = [];

        $expressableType = get_class($expressableObjects->first());
        $expressableModels = $this->obtainExpressableModel($expressableType);

        foreach ($expressableModels as $expressableModel)
            $expressionTypes[$expressableModel->expression_type_id] = $this->obtainExpressionType($expressableModel->expression_type_id);

        foreach ($expressableObjects as $object)
        {
            $userExpressions = Expression::where('expressable_type', $expressableType)
                                    ->where('expressable_id', $object->id)
                                    ->where('user_id', Auth::user()->id)
                                    ->get();
            foreach ($expressionTypes as $expressionType)
                $stats[$expressionType->id] = $this->stats($expressableType, $object->id, $expressionType);

            $userExpressionsGrouped = $userExpressions->groupBy('expression_type_id');

            array_push($expressableInfo, [
                    'expressable_object' => $object->toArray(),
                    'expressable_type' => $expressableType,                     // App\Models\Image
                    'expression_types' => $expressionTypes,                     // Like/Dislike
                    'user_expressions' => $userExpressionsGrouped->toArray(),
                    'stats' => $stats,
                 ]);
        }
        return $expressableInfo;
    }

    public function stats(String $expressable_type, int $expressable_id, ExpressionType $expressionType)
    {
        $expressions = Expression::where('expressable_id', $expressable_id)->where('expressable_type',$expressable_type)->get();
        $avg = $expressions->avg('expression');
        $count = $expressions->count();

        if ($expressionType->isRangeInt()) {
            $valueGrp = $expressions->groupBy('expression');
            $valueCounts = [];
            foreach ($valueGrp as $key => $grp)
                $valueCounts[$key] = $grp->count();

            return [
                'avg' => $avg,
                'count' => $count,
                'value_counts' => $valueCounts,
            ];
        }

        // float values
        return [
            'avg' => $avg,
            'count' => $count,
        ];
    }

    public function express(Object $object, $expression_type_id, $value)
    {
        // extract from object expressable_type, expressable_id
        // validate expressable_type, expressable_id - can it be express to?
        // validate expression - is it in range for expression-type?
        $validatedInput = ['expressable_type' => 'App\Models\Image', 'expressable_id' => $object->id, 'expression_type_id' => 7, 'expression' => $value];
        $expression = $this->storeOrUpdate($validatedInput);

        return $expression;
    }

    // different class or traits
    public function getIP()
    {
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip); // just to be safe
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
        return request()->ip(); // it will return server ip when no client ip found
    }
}
