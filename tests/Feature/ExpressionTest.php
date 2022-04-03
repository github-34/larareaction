<?php

namespace Tests\Feature;

use App\Models\Image;
use App\Models\User;

use App\Express\Models\ExpressableModel;
use App\Express\Models\Expression;
use App\Express\Models\ExpressionType;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * HTTP Tests for Expressions
 *
 * API Endpoints: /api/expressions[...]
 *
 * HTTP codes used below are the following.
 * HTTP_OK = 200                    Request successful
 * HTTP_UNAUTHORIZED = 401          Unauthenticated User
 * HTTP_FORBIDDEN = 403             Authenticated User attempted to access Forbidden Resource
 * HTTP_NOT_FOUND = 404             Not Found
 * HTTP_UNPROCESSABLE_ENTITY = 422  Unprocessable, invalid input
 *
 */
class ExpressionTest extends TestCase
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
        $response = $this->actingAs($user)->get('/api/expressions');
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /**
     * Get a single expression
     *
     * Api Endpoint: GET: /api/expressions/{expresion}
     *
     *
     *
     *
     */
    public function test_user_can_view_their_expression()
    {
        $this->seed();
        $user = User::all()->first();
        $response = $this->actingAs($user)->get('/api/expressions/1');
        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_user_cannot_view_others_expression()
    {
        $this->seed();
        $user = User::all()->first();
        $expression = Expression::where('user_id', '!=', $user->id)->first();
        $response = $this->actingAs($user)->get('/api/expressions/'.$expression->id);
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_user_cannot_view_nonexistent_expression()
    {
        $this->seed();
        $user = User::all()->first();
        $response = $this->actingAs($user)->get('/api/expressions/99999');
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_guest_cannot_view_expression()
    {
        $this->seed();
        $response = $this->get('/api/expressions');
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Store an expression
     *
     * Api Endpoint: POST: /api/expressions
     *
     *
     *
     *
     */
    // public function test_user_can_store_expression()
    // {
    //     $this->seed();

    //     $user = User::all()->first();
    //     $response = $this->actingAs($user)->post('/api/expressions', [
    //         'expression' => 1,
    //         'expressable_type' => 'App\Models\Image',
    //         'expressable_id' => 1
    //     ]);
    //     $response->assertStatus(Response::HTTP_OK);
    //     $this->assertTrue(array_key_exists('created_from', $response['data']) && !is_null($response['data']['created_from']));
    //     $this->assertTrue(array_key_exists('updated_from', $response['data']) && !is_null($response['data']['updated_from']));
    //     $this->assertTrue(array_key_exists('deleted_from', $response['data']) && is_null($response['data']['deleted_from']));
    // }

    // public function test_user_cannot_store_expressions_with_expressable_types_that_do_not_exist()
    // {
    //     $this->seed();

    //     $user = User::all()->first();
    //     $response = $this->actingAs($user)->post('/api/expressions', [
    //         'expression' => 1,
    //         'expressable_type' => 'App\Models\Image23423',
    //         'expressable_id' => 1
    //     ]);
    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    //     $response->assertSee('Invalid expressable type');
    // }

    // public function test_user_cannot_store_expressions_with_expressable_ids_that_do_not_exist()
    // {
    //     $this->seed();
    //     $user = User::all()->first();
    //     $response = $this->actingAs($user)->post('/api/expressions', [
    //         'expression' => 1,
    //         'expressable_type' => 'App\Models\Image',
    //         'expressable_id' => 1324234
    //     ]);
    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    //     $response->assertSee('Invalid expressable_id');
    // }

    // public function test_user_cannot_store_float_expressions_for_integer_expression_types()
    // {
    //     $this->seed();
    //     $user = User::all()->first();
    //     $response = $this->actingAs($user)->post('/api/expressions', [
    //         'expression' => 1.234234,
    //         'expressable_type' => 'App\Models\Image',
    //         'expressable_id' => 1
    //     ]);
    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    //     $response->assertSee('Invalid expression');
    // }

    // public function test_user_cannot_store_out_of_range_expressions()
    // {
    //     $this->seed();
    //     $user = User::all()->first();
    //     $response = $this->actingAs($user)->post('/api/expressions', [
    //         'expression' => 999999,
    //         'expressable_type' => 'App\Models\Image',
    //         'expressable_id' => 1
    //     ]);
    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    //     $response->assertSee('Invalid expression');
    // }

    // public function test_user_cannot_store_out_of_range_expressions2()
    // {
    //     $this->seed();
    //     $user = User::all()->first();
    //     $response = $this->actingAs($user)->post('/api/expressions', [
    //         'expression' => -1,
    //         'expressable_type' => 'App\Models\Image',
    //         'expressable_id' => 1
    //     ]);
    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    //     $response->assertSee('Invalid expression');
    // }

    // public function test_user_cannot_store_expressions_without_expression_type()
    // {
    //     $this->seed();
    //     $user = User::all()->first();
    //     $response = $this->actingAs($user)->post('/api/expressions', [
    //         'expression' => 1,
    //         'expressable_id' => 1
    //     ]);
    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    //     $response->assertSee('expressable_type is required');
    // }

    // public function test_user_cannot_store_expressions_without_expressable_id()
    // {
    //     $this->seed();
    //     $user = User::all()->first();
    //     $response = $this->actingAs($user)->post('/api/expressions', [
    //         'expression' => 1,
    //         'expressable_type' => 'App\Models\Image',
    //     ]);
    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    //     $response->assertSee('expressable_id is required');
    // }

    // public function test_user_cannot_store_expressions_without_expression()
    // {
    //     $this->seed();
    //     $user = User::all()->first();
    //     $response = $this->actingAs($user)->post('/api/expressions', [
    //         'expressable_type' => 'App\Models\Image',
    //         'expressable_id' => 1
    //     ]);
    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    //     $response->assertSee('expression is required');
    // }

    // public function test_guest_cannot_store_expression()
    // {
    //     $this->seed();
    //     $response = $this->get('/api/expressions');
    //     $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    // }

    // /**
    //  * Update an expression
    //  *
    //  * API endpoint: PATCH: /api/expressions/1
    //  *
    //  *
    //  *
    //  *
    //  *
    //  */
    // public function test_user_can_update_expression()
    // {
    //     $this->seed();

    //     $user = User::all()->first();
    //     $expression = Expression::where('user_id',$user->id)->first();

    //     $response = $this->actingAs($user)->patch('/api/expressions/'.$expression->id, [
    //         'expression' => 1,
    //     ]);
    //     $response->assertStatus(Response::HTTP_OK);
    //     $this->assertTrue(array_key_exists('updated_from', $response['data']) && !is_null($response['data']['updated_from']));
    // }

    // public function test_user_cannot_update_expression_greater_than_maximum()
    // {
    //     $this->seed();
    //     $user = User::all()->first();
    //     $expression = Expression::where('user_id',$user->id)->first();
    //     $response = $this->actingAs($user)->patch('/api/expressions/'.$expression->id, [
    //         'expression' => 999999,
    //     ]);
    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    //     $response->assertSee('expression cannot be greater than');
    // }

    // public function test_user_cannot_update_expressions_less_than_minimum()
    // {
    //     $this->seed();
    //     $user = User::all()->first();
    //     $expression = Expression::where('user_id',$user->id)->first();
    //     $response = $this->actingAs($user)->patch('/api/expressions/'.$expression->id, [
    //         'expression' => -1,
    //     ]);
    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    //     $response->assertSee('expression cannot be less than');
    // }

    // public function test_user_cannot_update_expressions_without_expression()
    // {
    //     $this->seed();
    //     $user = User::all()->first();
    //     $expression = Expression::where('user_id',$user->id)->first();
    //     $response = $this->actingAs($user)->patch('/api/expressions/'.$expression->id, [
    //         'field' => 1
    //     ]);
    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    //     $response->assertSee('The expression field is required');
    // }

    // public function test_guest_cannot_update_expression()
    // {
    //     $this->seed();

    //     $expression = Expression::all()->first();
    //     $response = $this->patch('/api/expressions/'.$expression->id, [
    //         'expression' => 1
    //     ]);
    //     $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    // }


    //  /**
    //   * Delete an expression
    //   *
    //   * Api Endpoint: DELETE: /api/expressions/1
    //   *
    //   *
    //   *
    //   *
    //   */
    // public function test_user_can_delete_their_expression()
    // {
    //     $this->seed();
    //     $user = User::all()->first();
    //     $expression = Expression::where('user_id',$user->id)->first();
    //     $response = $this->actingAs($user)->delete('/api/expressions/'.$expression->id);
    //     $response->assertSuccessful();
    //     $this->assertSoftDeleted($expression);
    // }

    // public function test_user_cannot_delete_others_expression()
    // {
    //     $this->seed();
    //     $user = User::all()->first();
    //     $expression = Expression::where('user_id','!=',$user->id)->first();
    //     $response = $this->actingAs($user)->delete('/api/expressions/'.$expression->id);
    //     $response->assertStatus(Response::HTTP_FORBIDDEN);
    // }

    // public function test_guest_cannot_delete_expressions()
    // {
    //     $this->seed();
    //     $expression = Expression::all()->first();
    //     $response = $this->delete('/api/expressions/'.$expression->id);
    //     $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    // }

    // /**
    //   * Get Expressable Stats
    //   *
    //   * Api Endpoint: POST: /api/expressions/stats
    //   *
    //   *
    //   *
    //   *
    //   */
    //   public function test_user_can_get_expressable_stats()
    //   {
    //     $this->seed();
    //     $user = User::all()->first();
    //     $response = $this->actingAs($user)->post('/api/expressions/stats', [
    //         'expressable_type' => 'App\Models\Image',
    //         'expressable_id' => 1
    //     ]);
    //     $response->assertStatus(Response::HTTP_OK);
    //     $response->assertSee('Retrieved stats successfully');
    //   }

    //   public function test_guest_can_get_expressable_stats()
    //   {
    //     $this->seed();
    //     $response = $this->post('/api/expressions/stats', [
    //         'expressable_type' => 'App\Models\Image',
    //         'expressable_id' => 1
    //     ]);
    //     $response->assertStatus(Response::HTTP_OK);
    //     $response->assertSee('Retrieved stats successfully');
    //   }

    /**
     * Test Expressions
     *
     */
    // public function test_user_can_express_applause()
    // {
    //     $this->seed();
    //     $response = $this->post('/api/expressions', [
    //         'expressable_type' => 'App\Models\Image',
    //         'expressable_id' => 1
    //         ''
    //     ]);
    //     $response->assertStatus(Response::HTTP_OK);
    //     $response->assertSee('Retrieved stats successfully');

    // }
}
