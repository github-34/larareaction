<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\User;

use App\Express\Models\ExpressableModel;
use App\Express\Models\Expression;
use App\Express\Models\ExpressionType;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Expression is always numeric: if range_type then integer, if non-range_type then float,
        // NOTE: for float (non-range_type) ranges, cannot have names for decimals values (3.56 => 'meh')
        ExpressionType::create([ 'id' => 1, 'description' => 'Applause',         'range_type' => 'int', 'min' => 1, 'max' => 1,
            'icons' => json_encode([ 1 => 'applause.jpg']),
            'labels' => json_encode([ 1 => 'applause' ])]);
        ExpressionType::create([ 'id' => 2, 'description' => 'Like - Dislike',   'range_type' => 'int', 'min' => 0, 'max' => 1,
            'icons' => json_encode([ 0 => 'thumbsdown.jpg', 1 => 'thumbsup.jpg']),
            'labels' => json_encode([ 0 => 'dislike', 1 => 'like' ])]);
        ExpressionType::create([ 'id' => 3, 'description' => 'Hot - Cold',       'range_type' => 'int', 'min' => 1, 'max' => 2, 'icons' => null, 'labels' => json_encode([ 1 => 'cold', 2 => 'hot' ])]);
        ExpressionType::create([ 'id' => 4, 'description' => 'Hot - Cold',       'range_type' => 'float','min' => 1, 'max' => 5, 'icons' => null, 'labels' => json_encode([ 1 => 'cold', 5 => 'hot'])]);
        ExpressionType::create([ 'id' => 5, 'description' => 'Cognitive',        'range_type' => 'int', 'min' => 1, 'max' => 3, 'icons' => null, 'labels' => json_encode([ 1 => 'interesting', 2 => 'clear as day', 3 => 'i\'m confused' ])  ]);
        ExpressionType::create([ 'id' => 6, 'description' => 'Emotive',          'range_type' => 'int', 'min' => 1, 'max' => 3, 'icons' => null, 'labels' => json_encode([ 1 => 'happy', 2 => 'super happy', 3 => 'sad' ])  ]);
        ExpressionType::create([ 'id' => 7, 'description' => '5-Star Rating',    'range_type' => 'int', 'min' => 1, 'max' => 5, 'icons' => null, 'labels' => json_encode([ 1 => '1 Star', 2=> '2 Stars', 3 => '3 Stars', 4 => '4 Stars', 5 => '5 Stars' ])]);
        ExpressionType::create([ 'id' => 8, 'description' => 'Michelin Stars',   'range_type' => 'int', 'min' => 1, 'max' => 3, 'icons' => null, 'labels' => json_encode([ 1 => '1 Michelin Star', 2=> '2 Michelin Stars', 3 => '3 Michelin Stars' ])]);
        ExpressionType::create([ 'id' => 9, 'description' => 'Vote',             'range_type' => 'int', 'min' => 1, 'max' => 3, 'icons' => null, 'labels' => json_encode([ 1 => 'in favor', 2 => 'against', 3 => 'abstain' ])]);

        ExpressionType::create([ 'id' => 10, 'description' => 'Elo Rating',      'range_type' => 'int', 'min' => 1, 'max' => 4000, 'labels' => json_encode(
            [ 0 => 'Novice',
                     1200 => 'Class D, Category 4',
                     1400 => 'Class C, Category 3',
                     1600 => 'Class B, Category 2',
                     1800 => 'Class A, Category 1',
                     2000 => 'Candidate Master',
                     2200 => 'Master',
                     2300 => 'International Master',
                     2400 => 'Grandmaster',
                     2700 => 'Super Grandmaster'
            ])
        ]);

        $user1 = User::create([ 'id' => 1, 'name' => 'User',   'email' => 'user1@users.com', 'email_verified_at' => now(), 'password' => Hash::make('password') ]);
        $user2 = User::create([ 'id' => 2, 'name' => 'User 2', 'email' => 'user2@users.com', 'email_verified_at' => now(), 'password' => Hash::make('password') ]);
        $user3 = User::create([ 'id' => 3, 'name' => 'User 3', 'email' => 'user3@users.com', 'email_verified_at' => now(), 'password' => Hash::make('password') ]);
        $user4 = User::create([ 'id' => 4, 'name' => 'User 3', 'email' => 'user4@users.com', 'email_verified_at' => now(), 'password' => Hash::make('password') ]);

        $images = Image::factory()->count(10)->create();

        ExpressableModel::create([ 'id' => 1, 'expressable_type' => 'App\Models\Image', 'expression_type_id' => 6 ]); // emotive
        ExpressableModel::create([ 'id' => 2, 'expressable_type' => 'App\Models\Image', 'expression_type_id' => 7 ]); // 5-star
        ExpressableModel::create([ 'id' => 3, 'expressable_type' => 'App\Models\User',  'expression_type_id' => 3 ]);

        // 5-Star Expressions to images[0]
        Expression::create([ 'user_id' => $user1->id, 'expressable_type' => 'App\Models\Image', 'expressable_id' => $images[0]->id, 'expression_type_id' => 6, 'expression' => 1, 'created_from' => '127.0.0.1']);
        Expression::create([ 'user_id' => $user2->id, 'expressable_type' => 'App\Models\Image', 'expressable_id' => $images[0]->id, 'expression_type_id' => 6, 'expression' => 3, 'created_from' => '32.2.12.23']);
        Expression::create([ 'user_id' => $user3->id, 'expressable_type' => 'App\Models\Image', 'expressable_id' => $images[0]->id, 'expression_type_id' => 6, 'expression' => 5, 'created_from' => '127.0.0.1']);
        Expression::create([ 'user_id' => $user4->id, 'expressable_type' => 'App\Models\Image', 'expressable_id' => $images[0]->id, 'expression_type_id' => 6, 'expression' => 5, 'created_from' => '43.22.3.22']);

        // Emotive Expressions to images[0]
        Expression::create([ 'user_id' => $user1->id, 'expressable_type' => 'App\Models\Image', 'expressable_id' => $images[0]->id, 'expression_type_id' => 7, 'expression' => 2, 'created_from' => '127.0.0.1']);
        Expression::create([ 'user_id' => $user2->id, 'expressable_type' => 'App\Models\Image', 'expressable_id' => $images[0]->id, 'expression_type_id' => 7, 'expression' => 1, 'created_from' => '32.2.12.23']);
        Expression::create([ 'user_id' => $user3->id, 'expressable_type' => 'App\Models\Image', 'expressable_id' => $images[0]->id, 'expression_type_id' => 7, 'expression' => 3, 'created_from' => '43.22.3.22']);

        // 5-Star Expressions to images[1]
        Expression::create([ 'user_id' => $user1->id, 'expressable_type' => 'App\Models\Image', 'expressable_id' => $images[1]->id, 'expression_type_id' => 6, 'expression' => 1, 'created_from' => '127.0.0.1']);
        Expression::create([ 'user_id' => $user2->id, 'expressable_type' => 'App\Models\Image', 'expressable_id' => $images[1]->id, 'expression_type_id' => 6, 'expression' => 2, 'created_from' => '32.2.12.23']);
        Expression::create([ 'user_id' => $user3->id, 'expressable_type' => 'App\Models\Image', 'expressable_id' => $images[1]->id, 'expression_type_id' => 6, 'expression' => 1, 'created_from' => '127.0.0.1']);
        Expression::create([ 'user_id' => $user4->id, 'expressable_type' => 'App\Models\Image', 'expressable_id' => $images[1]->id, 'expression_type_id' => 6, 'expression' => 1, 'created_from' => '43.22.3.22']);

        // Emotive Expressions to images[1]
        Expression::create([ 'user_id' => $user1->id, 'expressable_type' => 'App\Models\Image', 'expressable_id' => $images[1]->id, 'expression_type_id' => 7, 'expression' => 1, 'created_from' => '127.0.0.1']);
        Expression::create([ 'user_id' => $user2->id, 'expressable_type' => 'App\Models\Image', 'expressable_id' => $images[1]->id, 'expression_type_id' => 7, 'expression' => 1, 'created_from' => '32.2.12.23']);
        Expression::create([ 'user_id' => $user4->id, 'expressable_type' => 'App\Models\Image', 'expressable_id' => $images[1]->id, 'expression_type_id' => 7, 'expression' => 2, 'created_from' => '43.22.3.22']);

        // 5-Star Expressions to images[2]
        Expression::create([ 'user_id' => $user1->id, 'expressable_type' => 'App\Models\Image', 'expressable_id' => $images[2]->id, 'expression_type_id' => 6, 'expression' => 1, 'created_from' => '127.0.0.1']);
        Expression::create([ 'user_id' => $user2->id, 'expressable_type' => 'App\Models\Image', 'expressable_id' => $images[2]->id, 'expression_type_id' => 6, 'expression' => 2, 'created_from' => '32.2.12.23']);
        Expression::create([ 'user_id' => $user4->id, 'expressable_type' => 'App\Models\Image', 'expressable_id' => $images[2]->id, 'expression_type_id' => 6, 'expression' => 1, 'created_from' => '43.22.3.22']);

        // Emotive Expressions to images[2]
        Expression::create([ 'user_id' => $user1->id, 'expressable_type' => 'App\Models\Image', 'expressable_id' => $images[2]->id, 'expression_type_id' => 7, 'expression' => 2, 'created_from' => '127.0.0.1']);
        Expression::create([ 'user_id' => $user2->id, 'expressable_type' => 'App\Models\Image', 'expressable_id' => $images[2]->id, 'expression_type_id' => 7, 'expression' => 3, 'created_from' => '32.2.12.23']);
        Expression::create([ 'user_id' => $user3->id, 'expressable_type' => 'App\Models\Image', 'expressable_id' => $images[2]->id, 'expression_type_id' => 7, 'expression' => 3, 'created_from' => '43.22.3.22']);
        Expression::create([ 'user_id' => $user4->id, 'expressable_type' => 'App\Models\Image', 'expressable_id' => $images[2]->id, 'expression_type_id' => 7, 'expression' => 2, 'created_from' => '43.22.3.22']);
        // 5 users with 5 images each and an expression for each
        // User::factory(5)
        //     ->has(Image::factory()->
        //         hasExpressions(1,function (array $attributes, Image $image) {
        //             return ['user_id' => $image->user_id];
        //         })->count(5), 'images')
        //     ->create();
        //User::factory()
    }
}
