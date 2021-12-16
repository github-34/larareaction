<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\ReactableModel;
use App\Models\Reaction;
use App\Models\ReactionType;
use App\Models\User;
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
        $this->call(ReactionTypeSeeder::class);
        // $this->call(ReactionSeeder::class);

        // Reaction is always numeric: if range_type then integer, if non-range_type then float,
        // NOTE: for float (non-range_type) ranges, cannot have names for decimals values (3.56 => 'meh')
        ReactionType::create([ 'id' => 1, 'description' => 'Applause',         'range_type' => 'int', 'min' => 1, 'max' => 1,
            'icons' => json_encode([ 1 => 'applause.jpg']),
            'labels' => json_encode([ 1 => 'applause' ])]);
        ReactionType::create([ 'id' => 2, 'description' => 'Like - Dislike',   'range_type' => 'int', 'min' => 0, 'max' => 1,
            'icons' => json_encode([ 0 => 'thumbsdown.jpg', 1 => 'thumbsup.jpg']),
            'labels' => json_encode([ 0 => 'dislike', 1 => 'like' ])]);
        ReactionType::create([ 'id' => 3, 'description' => 'Hot - Cold',       'range_type' => 'int', 'min' => 1, 'max' => 2, 'icons' => null, 'labels' => json_encode([ 1 => 'cold', 2 => 'hot' ])]);
        ReactionType::create([ 'id' => 4, 'description' => 'Hot - Cold',       'range_type' => 'float','min' => 1, 'max' => 5, 'icons' => null, 'labels' => json_encode([ 1 => 'cold', 5 => 'hot'])]);
        ReactionType::create([ 'id' => 5, 'description' => 'Cognitive',        'range_type' => 'int', 'min' => 1, 'max' => 3, 'icons' => null, 'labels' => json_encode([ 1 => 'interesting', 2 => 'clear as day', 3 => 'i\'m confused' ])  ]);
        ReactionType::create([ 'id' => 6, 'description' => 'Emotive',          'range_type' => 'int', 'min' => 1, 'max' => 3, 'icons' => null, 'labels' => json_encode([ 1 => 'happy', 2 => 'super happy', 3 => 'sad' ])  ]);
        ReactionType::create([ 'id' => 7, 'description' => '5-Star Rating',    'range_type' => 'int', 'min' => 1, 'max' => 5, 'icons' => null, 'labels' => null]);
        ReactionType::create([ 'id' => 8, 'description' => 'Michelin Stars',   'range_type' => 'int', 'min' => 1, 'max' => 3, 'icons' => null, 'labels' => json_encode([ 1 => '1 Michelin Star', 2=> '2 Michelin Stars', 3 => '3 Michelin Stars' ])]);
        ReactionType::create([ 'id' => 9, 'description' => 'Vote',             'range_type' => 'int', 'min' => 1, 'max' => 3, 'icons' => null, 'labels' => json_encode([ 1 => 'in favor', 2 => 'against', 3 => 'abstain' ])]);

        ReactionType::create([ 'id' => 10, 'description' => 'Elo Rating',      'range_type' => 'int', 'min' => 1, 'max' => 4000, 'labels' => json_encode(
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

        $image1 = Image::create([ 'id' => 1, 'user_id' => $user1->id, 'title' => 'Awesome Image','url' => 'https://upload.wikimedia.org/wikipedia/commons/e/ec/Mona_Lisa%2C_by_Leonardo_da_Vinci%2C_from_C2RMF_retouched.jpg', 'public' => true ]);
        $image2 = Image::create([ 'id' => 2, 'user_id' => $user1->id, 'title' => 'Ok Image',     'url' => 'https://upload.wikimedia.org/wikipedia/commons/6/6a/Leonardo_da_Vinci_-_Portrait_of_a_Musician_-_Pinacoteca_Ambrosiana.jpg', 'public' => true ]);
        $image3 = Image::create([ 'id' => 3, 'user_id' => $user2->id, 'title' => 'Cool Image',   'url' => 'https://upload.wikimedia.org/wikipedia/commons/2/26/Da_Vinci_Vitruve_Luc_Viatour_%28cropped%29.jpg', 'public' => false ]);
        $image4 = Image::create([ 'id' => 4, 'user_id' => $user2->id, 'title' => 'Image 5',      'url' => 'https://upload.wikimedia.org/wikipedia/commons/5/54/Study_of_horse.jpg', 'public' => false ]);

        $reaction1 = Reaction::create([ 'id' => 1, 'user_id' => $user1->id, 'reactable_type' => 'App\Models\Image', 'reactable_id' => $image1->id, 'reaction' => 1, 'created_from' => '127.0.0.1']);
        $reaction2 = Reaction::create([ 'id' => 2, 'user_id' => $user1->id, 'reactable_type' => 'App\Models\Image', 'reactable_id' => $image3->id, 'reaction' => 2, 'created_from' => '32.2.12.23']);
        $reaction3 = Reaction::create([ 'id' => 3, 'user_id' => $user2->id, 'reactable_type' => 'App\Models\Image', 'reactable_id' => $image2->id, 'reaction' => 3, 'created_from' => '127.0.0.1']);
        $reaction4 = Reaction::create([ 'id' => 4, 'user_id' => $user1->id, 'reactable_type' => 'App\Models\Image', 'reactable_id' => $image4->id, 'reaction' => 3, 'created_from' => '43.22.3.22']);

        ReactableModel::create([ 'id' => 1, 'reactable_type' => 'App\Models\Image', 'reaction_type_id' => 8 ]);
        ReactableModel::create([ 'id' => 2, 'reactable_type' => 'App\Models\User',  'reaction_type_id' => 3 ]);
        // 5 users with 5 images each and a reaction for each
        // User::factory(5)
        //     ->has(Image::factory()->
        //         hasReactions(1,function (array $attributes, Image $image) {
        //             return ['user_id' => $image->user_id];
        //         })->count(5), 'images')
        //     ->create();
    }
}
