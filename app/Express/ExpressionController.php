<?php

namespace App\Express;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\ValidationException;

use App\Http\Controllers\Controller;
use App\Models\User;

use App\Express\ExpressionService;
use App\Express\Requests\StoreExpressionRequest;
use App\Express\Requests\UpdateExpressionRequest;
use App\Express\Requests\ShowExpressionStatsRequest;
use App\Express\Models\ExpressableModel;
use App\Express\Models\Expression;
use App\Express\Models\ExpressionType;

/**
 * Expression Controller
 *
 * Expression controller handles all web & api expression requests, typically in the following order.
 *      1. authorize request (always)
 *      2. validate non-form input (if necessary)
 *      3. call services methods, send notifications, dispatch jobs (if necessary)
 *      4. assemble and return api response (always)
 *
 * @version 0.1
 * @access public
 * @todo
 */
class ExpressionController extends Controller
{
    protected $service;

    public function __construct(ExpressionService $service)
    {
        $this->service = $service;

        if (App::environment() == 'local' && request()->is('api/*'))
            Auth::login(User::find(1));
    }

    public function show(Expression $expression)
    {
        $this->authorize('view', $expression);

        return $this->successResponse([
                'expression' => $expression ],
                'Expression retrieved successfully'
        );
    }

    /**
     * Note: expressable_id is assumed to exit. There is no validation on whether that model exists.
     */
    public function storeOrUpdate(StoreExpressionRequest $request)
    {
        $this->authorize('create', Expression::class);

        $validated = $request->validated();
        $expression = $this->service->storeOrUpdate($validated);

        return $this->successResponse($expression, 'Expression created successfully');
    }

    /**
     * Note: expressable_id is assumed to exit. There is no validation on whether that model exists.
     * Can only update expression; not expressable_type or expressable_id
     */
    public function update(UpdateExpressionRequest $request, Expression $expression)
    {
        $this->authorize('update', $expression);

        $validated = $request->validated();

        // Validate expression is in min-max range as defined in its expression type
        $expressableModel = ExpressableModel::where('expressable_type', $expression->expressable_type)->first();
        $expressionType = ExpressionType::where('id', '=', $expressableModel->expression_type_id)->first();
        if ($validated['expression'] < $expressionType->min)
            throw ValidationException::withMessages(['expression' => [ "expression cannot be less than " . $expressionType->min]]);
        if ($validated['expression'] > $expressionType->max)
            throw ValidationException::withMessages(['expression' => [ "expression cannot be greater than " . $expressionType->max]]);

        $expression = $this->service->update($expression, $validated);

        return $this->successResponse($expression, 'Expression updated successfully');
    }

    public function destroy(Expression $expression)
    {
        $this->authorize('delete', $expression);

        $this->service->delete($expression);

        return $this->successResponse(true, 'Expression deleted successfully');
    }

    // should stats return null for expressables that don't exist
    // how to distinguish between a request for a model with no views/emotions and a model that was never created??
    public function stats(ShowExpressionStatsRequest $request)
    {
        $validated = $request->validated();

        $this->authorize('viewStats', Expression::class);

        $stats  = $this->service->stats(
            $validated['expressable_type'],
            $validated['expressable_id'],
            $this->service->obtainExpressionType($validated['expression_type_id']));

        return $this->successResponse($stats, 'Retrieved stats successfully');
    }

    public function successResponse($data, $message = '')
    {
        return request()->is('api') ?
            response()->json(['status' => 'ok', 'http_code' => 200, 'data' => $data, 'message' => $message]) :
            redirect('dashboard')->with('status', 'Expression recorded!');
    }
    // public function errorResponse($message, int $code) : JsonResponse {
    //     return response()->json(['error' => $message, 'code' => $code ]);
    // }

    // public function errorViewResponse($code) {
    //     return response()->view('errors.404');
    // }
}
