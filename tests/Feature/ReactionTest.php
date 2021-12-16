<?php

namespace Tests\Feature;

use App\Models\Image;
use App\Models\ReactableModel;
use App\Models\Reaction;
use App\Models\ReactionType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * HTTP Tests for Reactions
 *
 * API Endpoints: /api/reactions[...]
 *
 * HTTP codes used below are the following.
 * HTTP_OK = 200                    Request successful
 * HTTP_UNAUTHORIZED = 401          Unauthenticated User
 * HTTP_FORBIDDEN = 403             Authenticated User attempted to access Forbidden Resource
 * HTTP_NOT_FOUND = 404             Not Found
 * HTTP_UNPROCESSABLE_ENTITY = 422  Unprocessable, invalid input
 *
 */
class ReactionTest extends TestCase
{

    use RefreshDatabase;

    /**
     * General HTTP Errors
     *
     *
     */
    public function test_user_cannot_access_undefined_endpoint()
    {
        $this->seed();
        $user = User::all()->first();
        $response = $this->actingAs($user)->get('/api/reactionsasdf');
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }


    /**
     * Get all reactions
     *
     * Api Endpoint: GET: /api/reactions
     *
     *
     *
     *
     */
    public function test_user_can_view_all_reactions()
    {
        $this->seed();
        $user = User::all()->first();
        $response = $this->actingAs($user)->get('/api/reactions');
        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_guest_cannot_view_all_reactions()
    {
        $this->seed();
        $response = $this->get('/api/reactions');
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Get a single reaction
     *
     * Api Endpoint: GET: /api/reactions/{reaction}
     *
     *
     *
     *
     */
    public function test_user_can_view_their_reaction()
    {
        $this->seed();
        $user = User::all()->first();
        $response = $this->actingAs($user)->get('/api/reactions/1');
        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_user_cannot_view_others_reaction()
    {
        $this->seed();
        $user = User::all()->first();
        $reaction = Reaction::where('user_id', '!=', $user->id)->first();
        $response = $this->actingAs($user)->get('/api/reactions/'.$reaction->id);
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_user_cannot_view_nonexistent_reaction()
    {
        $this->seed();
        $user = User::all()->first();
        $response = $this->actingAs($user)->get('/api/reactions/99999');
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_guest_cannot_view_reaction()
    {
        $this->seed();
        $response = $this->get('/api/reactions');
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Store a reaction
     *
     * Api Endpoint: POST: /api/reactions
     *
     *
     *
     *
     */
    public function test_user_can_store_reaction()
    {
        $this->seed();

        $user = User::all()->first();
        // reactableModel
        //
        $response = $this->actingAs($user)->post('/api/reactions', [
            'reaction' => 1,
            'reactable_type' => 'App\Models\Image',
            'reactable_id' => 1
        ]);
        $response->assertStatus(Response::HTTP_OK);
        $this->assertTrue(array_key_exists('created_from', $response['data']) && !is_null($response['data']['created_from']));
        $this->assertTrue(array_key_exists('updated_from', $response['data']) && !is_null($response['data']['updated_from']));
        $this->assertTrue(array_key_exists('deleted_from', $response['data']) && is_null($response['data']['deleted_from']));
    }

    public function test_user_cannot_store_reactions_with_reactable_types_that_do_not_exist()
    {
        $this->seed();

        $user = User::all()->first();
        $response = $this->actingAs($user)->post('/api/reactions', [
            'reaction' => 1,
            'reactable_type' => 'App\Models\Image23423',
            'reactable_id' => 1
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertSee('Invalid reactable type');
    }

    public function test_user_cannot_store_reactions_with_reactable_ids_that_do_not_exist()
    {
        $this->seed();
        $user = User::all()->first();
        $response = $this->actingAs($user)->post('/api/reactions', [
            'reaction' => 1,
            'reactable_type' => 'App\Models\Image',
            'reactable_id' => 1324234
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertSee('Invalid reactable_id');
    }

    public function test_user_cannot_store_float_reactions_for_integer_reaction_types()
    {
        $this->seed();
        $user = User::all()->first();
        $response = $this->actingAs($user)->post('/api/reactions', [
            'reaction' => 1.234234,
            'reactable_type' => 'App\Models\Image',
            'reactable_id' => 1
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertSee('Invalid reaction');
    }

    public function test_user_cannot_store_out_of_range_reactions()
    {
        $this->seed();
        $user = User::all()->first();
        $response = $this->actingAs($user)->post('/api/reactions', [
            'reaction' => 999999,
            'reactable_type' => 'App\Models\Image',
            'reactable_id' => 1
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertSee('Invalid reaction');
    }

    public function test_user_cannot_store_out_of_range_reactions2()
    {
        $this->seed();
        $user = User::all()->first();
        $response = $this->actingAs($user)->post('/api/reactions', [
            'reaction' => -1,
            'reactable_type' => 'App\Models\Image',
            'reactable_id' => 1
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertSee('Invalid reaction');
    }

    public function test_user_cannot_store_reactions_without_reaction_type()
    {
        $this->seed();
        $user = User::all()->first();
        $response = $this->actingAs($user)->post('/api/reactions', [
            'reaction' => 1,
            'reactable_id' => 1
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertSee('reactable_type is required');
    }

    public function test_user_cannot_store_reactions_without_reactable_id()
    {
        $this->seed();
        $user = User::all()->first();
        $response = $this->actingAs($user)->post('/api/reactions', [
            'reaction' => 1,
            'reactable_type' => 'App\Models\Image',
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertSee('reactable_id is required');
    }

    public function test_user_cannot_store_reactions_without_reaction()
    {
        $this->seed();
        $user = User::all()->first();
        $response = $this->actingAs($user)->post('/api/reactions', [
            'reactable_type' => 'App\Models\Image',
            'reactable_id' => 1
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertSee('reaction is required');
    }

    public function test_guest_cannot_store_reaction()
    {
        $this->seed();
        $response = $this->get('/api/reactions');
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Update a reaction
     *
     * API endpoint: PATCH: /api/reactions/1
     *
     *
     *
     *
     *
     */
    public function test_user_can_update_reaction()
    {
        $this->seed();

        $user = User::all()->first();
        $reaction = Reaction::where('user_id',$user->id)->first();

        $response = $this->actingAs($user)->patch('/api/reactions/'.$reaction->id, [
            'reaction' => 1,
        ]);
        $response->assertStatus(Response::HTTP_OK);
        $this->assertTrue(array_key_exists('updated_from', $response['data']) && !is_null($response['data']['updated_from']));
    }

    public function test_user_cannot_update_reaction_greater_than_maximum()
    {
        $this->seed();
        $user = User::all()->first();
        $reaction = Reaction::where('user_id',$user->id)->first();
        $response = $this->actingAs($user)->patch('/api/reactions/'.$reaction->id, [
            'reaction' => 999999,
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertSee('reaction cannot be greater than');
    }

    public function test_user_cannot_update_reactions_less_than_minimum()
    {
        $this->seed();
        $user = User::all()->first();
        $reaction = Reaction::where('user_id',$user->id)->first();
        $response = $this->actingAs($user)->patch('/api/reactions/'.$reaction->id, [
            'reaction' => -1,
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertSee('reaction cannot be less than');
    }

    public function test_user_cannot_update_reactions_without_reaction()
    {
        $this->seed();
        $user = User::all()->first();
        $reaction = Reaction::where('user_id',$user->id)->first();
        $response = $this->actingAs($user)->patch('/api/reactions/'.$reaction->id, [
            'field' => 1
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertSee('The reaction field is required');
    }

    public function test_guest_cannot_update_reaction()
    {
        $this->seed();

        $reaction = Reaction::all()->first();
        $response = $this->patch('/api/reactions/'.$reaction->id, [
            'reaction' => 1
        ]);
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }


     /**
      * Delete a reaction
      *
      * Api Endpoint: DELETE: /api/reactions/1
      *
      *
      *
      *
      */
    public function test_user_can_delete_their_reaction()
    {
        $this->seed();
        $user = User::all()->first();
        $reaction = Reaction::where('user_id',$user->id)->first();
        $response = $this->actingAs($user)->delete('/api/reactions/'.$reaction->id);
        $response->assertSuccessful();
        $this->assertSoftDeleted($reaction);
    }

    public function test_user_cannot_delete_others_reaction()
    {
        $this->seed();
        $user = User::all()->first();
        $reaction = Reaction::where('user_id','!=',$user->id)->first();
        $response = $this->actingAs($user)->delete('/api/reactions/'.$reaction->id);
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_guest_cannot_delete_reactions()
    {
        $this->seed();
        $reaction = Reaction::all()->first();
        $response = $this->delete('/api/reactions/'.$reaction->id);
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
      * Get Reactable Stats
      *
      * Api Endpoint: POST: /api/reactions/stats
      *
      *
      *
      *
      */
      public function test_user_can_get_reactable_stats()
      {
        $this->seed();
        $user = User::all()->first();
        $response = $this->actingAs($user)->post('/api/reactions/stats', [
            'reactable_type' => 'App\Models\Image',
            'reactable_id' => 1
        ]);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee('Retrieved stats successfully');
      }

      public function test_guest_can_get_reactable_stats()
      {
        $this->seed();
        $response = $this->post('/api/reactions/stats', [
            'reactable_type' => 'App\Models\Image',
            'reactable_id' => 1
        ]);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee('Retrieved stats successfully');
      }
}
