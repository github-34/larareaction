<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;

use App\Http\Requests\StoreReactionRequest;
use App\Http\Requests\UpdateReactionRequest;
use App\Http\Requests\ReactionStatsRequest;

use App\Models\ReactableModel;
use App\Models\Reaction;
use App\Models\ReactionType;
use App\Models\User;

use App\Services\ReactionService;
use Illuminate\Validation\ValidationException;

/**
 * Reaction Controller
 *
 * Reaction controller handles all reaction (api/reaction/*) requests, typically in the following order.
 *      1. authorize request (always)
 *      2. validate non-form input (if necessary)
 *      3. call services methods, send notifications, dispatch jobs (if necessary)
 *      4. assemble and return api response (always)
 *
 * @version 0.1
 * @access public
 * @todo
 */
class ReactionController extends Controller
{
    protected $service;

    public function __construct(ReactionService $service)
    {
        $this->service = $service;

        if (App::environment() == 'local')
            Auth::login(User::find(1));
    }

    public function index()
    {
        $this->authorize('viewAny', Reaction::class);

        return $this->apiSuccessResponse($this->service->obtainAll(), 'Retrieved all reaction successfully');
    }

    public function show(Reaction $reaction)
    {
        $this->authorize('view', $reaction);

        return $this->apiSuccessResponse([
                'reaction' => $reaction,
                'stats' => $this->service->stats($reaction->reactable_type, $reaction->reactable_id)
            ],
            'Reaction retrieved successfully'
        );
    }

    /**
     * Note: reactable_id is assumed to exit. There is no validation on whether that model exists.
     */
    public function storeOrUpdate(StoreReactionRequest $request)
    {
        $this->authorize('create', Reaction::class);

        $validated = $request->validated();

        $reaction = $this->service->storeOrUpdate($validated);

        return $this->apiSuccessResponse($reaction, 'Reaction created successfully');
    }

    /**
     * Note: reactable_id is assumed to exit. There is no validation on whether that model exists.
     * Can only update reaction; not reactable_type or reactable_id
     */
    public function update(UpdateReactionRequest $request, Reaction $reaction)
    {
        $this->authorize('update', $reaction);

        $validated = $request->validated();

        // Validate reaction is in min-max range as defined in its reaction type
        $reactableModel = ReactableModel::where('reactable_type', '=', $reaction->reactable_type)->first();
        $reactionType = ReactionType::where('id', '=', $reactableModel->reaction_type_id)->first();
        if ($validated['reaction'] < $reactionType->min)
            throw ValidationException::withMessages(['reaction' =>   ["reaction cannot be less than " . $reactionType->min]]);
        if ($validated['reaction'] > $reactionType->max)
            throw ValidationException::withMessages(['reaction' => ["reaction cannot be greater than " . $reactionType->max]]);

        $reaction = $this->service->update($reaction, $validated);

        return $this->apiSuccessResponse($reaction, 'Reaction updated successfully');
    }

    public function destroy(Reaction $reaction)
    {
        $this->authorize('delete', $reaction);

        $this->service->delete($reaction);

        return $this->apiSuccessResponse(true, 'Reaction deleted successfully');
    }

    public function types()
    {
        $this->authorize('viewAnyReactionType', Reaction::class);

        return $this->apiSuccessResponse($this->service->obtainAllReactionTypes(), 'Reaction types retrieved successfully');
    }

    // should stats return null for reactables that don't exist
    // how to distinguish between a request for a model with no views/emotions and a model that was never created??
    public function stats(ReactionStatsRequest $request)
    {
        $validated = $request->validated();

        // TODO: authorization: can guest users get stats for any reactable/reactable_id????
        // $this->authorize('viewStats', $reactable, $reactable_id);

        $stats  = $this->service->stats($validated['reactable_type'], $validated['reactable_id']);

        return $this->apiSuccessResponse($stats, 'Retrieved stats successfully');
    }

    public function apiSuccessResponse($data, $message = '')
    {
        return response()->json(['status' => 'ok', 'http_code' => 200, 'data' => $data, 'message' => $message]);
    }

    public function isStringFloat(String $str)
    {
        return is_numeric($str) && strpos($str, '.') !== false;
    }
}
