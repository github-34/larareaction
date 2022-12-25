<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Insomnicles\Laraexpress\ExpressionType;

class LaraexpressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // Expression is always numeric: if range_type then integer, if non-range_type then float,
        // NOTE: for float (non-range_type) ranges, cannot have names for decimals values (3.56 => 'meh')
        ExpressionType::create(['id' => 1, 'description' => 'Applause',         'range_type' => 'int', 'min' => 1, 'max' => 1, 'icons' => json_encode([1 => 'applause.jpg']), 'labels' => json_encode([1 => 'applause'])]);
        ExpressionType::create(['id' => 2, 'description' => 'Like - Dislike',   'range_type' => 'int', 'min' => 0, 'max' => 1, 'icons' => json_encode([0 => 'thumbsdown.jpg', 1 => 'thumbsup.jpg']), 'labels' => json_encode([0 => 'dislike', 1 => 'like'])]);
        ExpressionType::create(['id' => 3, 'description' => 'Hot - Cold',       'range_type' => 'int', 'min' => 1, 'max' => 2, 'icons' => null, 'labels' => json_encode([1 => 'cold', 2 => 'hot'])]);
        ExpressionType::create(['id' => 4, 'description' => 'Hot - Cold',       'range_type' => 'float', 'min' => 1, 'max' => 5, 'icons' => null, 'labels' => json_encode([1 => 'cold', 5 => 'hot'])]);
        ExpressionType::create(['id' => 5, 'description' => 'Cognitive',        'range_type' => 'int', 'min' => 1, 'max' => 3, 'icons' => null, 'labels' => json_encode([1 => 'interesting', 2 => 'clear as day', 3 => 'i\'m confused'])]);
        ExpressionType::create(['id' => 6, 'description' => 'Emotive',          'range_type' => 'int', 'min' => 1, 'max' => 3, 'icons' => null, 'labels' => json_encode([1 => 'happy', 2 => 'super happy', 3 => 'sad'])]);
        ExpressionType::create(['id' => 7, 'description' => '5-Star Rating',    'range_type' => 'int', 'min' => 1, 'max' => 5, 'icons' => null, 'labels' => json_encode([1 => '1 Star', 2=> '2 Stars', 3 => '3 Stars', 4 => '4 Stars', 5 => '5 Stars'])]);
        ExpressionType::create(['id' => 8, 'description' => 'Michelin Stars',   'range_type' => 'int', 'min' => 1, 'max' => 3, 'icons' => null, 'labels' => json_encode([1 => '1 Michelin Star', 2=> '2 Michelin Stars', 3 => '3 Michelin Stars'])]);
        ExpressionType::create(['id' => 9, 'description' => 'Vote',             'range_type' => 'int', 'min' => 1, 'max' => 3, 'icons' => null, 'labels' => json_encode([1 => 'in favor', 2 => 'against', 3 => 'abstain'])]);
        ExpressionType::create(['id' => 10, 'description' => 'Elo Rating',      'range_type' => 'int', 'min' => 1, 'max' => 4000, 'labels' => json_encode([ 0 => 'Novice', 1200 => 'Class D, Category 4', 1400 => 'Class C, Category 3', 1600 => 'Class B, Category 2', 1800 => 'Class A, Category 1', 2000 => 'Candidate Master', 2200 => 'Master', 2300 => 'International Master', 2400 => 'Grandmaster', 2700 => 'Super Grandmaster' ])]);
    }
}
