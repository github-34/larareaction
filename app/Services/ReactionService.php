<?php

namespace App\Services;

use App\Models\ReactableModel;
use App\Models\Reaction;
use App\Models\ReactionType;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class ReactionService
{

    public function obtainAll(): Collection
    {
        return Reaction::all();
    }

    public function obtain(int $id): Reaction
    {
        return Reaction::findOrFail($id);
    }

    public function storeOrUpdate(array $validatedInput): Reaction
    {
        $reaction = Reaction::where([
            'reactable_type' => $validatedInput['reactable_type'],
            'reactable_id' => $validatedInput['reactable_id'],
            'user_id' => Auth::user()->id,
        ])->first();

        $inputReaction = $validatedInput['reaction'];
        $reactionValue = (is_numeric($inputReaction) && strpos($inputReaction, ".") !== false) ? floatval($inputReaction) : intval($inputReaction);

        // Create new Reaction
        if (is_null($reaction))
            return Reaction::create([
                'reactable_type' => $validatedInput['reactable_type'],
                'reactable_id' => $validatedInput['reactable_id'],
                'user_id' => Auth::user()->id,
                'reaction' => $reactionValue,
                'created_from' => $this->getIP()
            ]);

        // Update Existing Reaction
        $updatedReaction = $reaction->fill([
            'reactable_type' => $validatedInput['reactable_type'],
            'reactable_id' => $validatedInput['reactable_id'],
            'user_id' => Auth::user()->id,
            'reaction' => $reactionValue,
            'updated_from' => $this->getIP()
        ]);
        $updatedReaction->save();

        return $updatedReaction;
    }

    public function update(Reaction $reaction, array $validatedInput): Reaction
    {
        return $reaction->fill(array_merge($validatedInput, ['updated_from' => $this->getIP()]));
    }

    public function delete(Reaction $reaction): void
    {
        $reaction->deleted_from = $this->getIP();
        $reaction->save();
        $reaction->delete();
    }

    public function obtainAllReactionTypes()
    {
        return ReactionType::all();
    }


    public function stats(String $reactable_type, int $reactable_id)
    {

        $reactableObject = ReactableModel::where('reactable_type', $reactable_type)->first();
        $type = ReactionType::find($reactableObject->reaction_type_id);
        $reactions = Reaction::where('reactable_id', $reactable_id)->get();
        $avg = $reactions->avg('reaction');
        $count = $reactions->count();

        if ($type->isRangeInt()) {
            $valueGrp = $reactions->groupBy('reaction');
            $valueCounts = [];
            foreach ($valueGrp as $key => $grp)
                $valueCounts[$key] = $grp->count();

            return [
                'median' => $avg,
                'count' => $count,
                'value_counts' => $valueCounts,
                'type' => $type->toArray()
            ];
        }

        // float values
        return [
            'median' => $avg,
            'count' => $count,
            'type' => $type->toArray()
        ];
    }

    public function react(Object $object, $value)
    {
        // extract from object reactable_type, reactable_id
        // validate reactable_type, reactable_id - can it be reacted to?
        // validate reaction - is it in range for reaction-type?
        $validatedInput = ['reactable_type' => 'App\Models\Image', 'reactable_id' => $object->id, 'reaction' => $value];
        // dd($validatedInput);
        $reaction = $this->storeOrUpdate($validatedInput);

        return $reaction;
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
