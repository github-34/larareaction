## Laraexpress

A simple way of handling expressions, reactions and ratings in Laravel.

Source code for packagist.

## Features
- Terminology
    * *ExpressionType*: a type of expression, reaction or rating (e.g. 1 to 5 stars, Like/Dislike,  )
    * *ExpressableModel*: a laravel model that *can* have expressions
    * *Expressable Id*: id for the ExpressableModel
    * *Expression*: expression value

- Expressions
    * Several build-in Expression types with discrete or gradient ranges
    * Custom expressions can be added
    * Expressable models (models that *can* have expressions) must be predefined in DB
    * Expressable models can have multiple expression types
        - E.g. Image model can have two expression types: like/dislike AND 5-star rating)
    * One user expression per expressable model-expression type pair
    * Only authenticated users can store expressions
    * Users can only update or delete their own expressions
    * Users can only have expressions to predefined set of expressable models

- Pre-defined Expression Types
    * Applause
    * Like/Dislike
    * Hot - Cold discrete
    * Hot - Cold gradient
    * Cognitive: interesting, clear, confused
    * Emotive: Happy, Sad, Super Happy
    * Five Star
    * Michelin Stars: 1, 2 or 3 stars
    * Vote: in favor, against, abstain
    * ELO Chess Rating: 1200 or up

## Installation

```
    composer require insomnicles/laraexpress
```

## Usage
- Define which models can have expressions. For example, to make Image models expressable with a five star rating and an emotive expression add the following to a DB seeder:

```
    ExpressableModel::create([ 'expressable_type' => 'App\Models\Image', 'expression_type_id' => Xpress::FIVESTAR ]);
    ExpressableModel::create([ 'expressable_type' => 'App\Models\Image', 'expression_type_id' => Xpress::EMOTIVE ]);

```
- creating expressions/rating using Facade: Express::express($object, $type_id, $expression)

```
    $expr = Express::express($image, Xpress::FIVESTAR, Express::TWOSTARS);
    $expr = Express::express($product, Xpress::FIVESTAR, Express::FIVESTARS);
    $expr = Express::express($restaurant, Xpress::MICHELINSTAR, Express::ONEMICHELINSTARS);
    $expr = Express::express($image, Xpress::LIKEDISLIKE, Express::LIKE);
    $expr = Express::express($image, Xpress::HOTCOLDGRADIENT, 2.563);
    $expr = Express::express($image, Xpress::EMOTIVE, Express::HAPPY);
    $expr = Express::express($image, Xpress::EMOTIVE, Express::MAD);
    $expr = Express::express($image, Xpress::COGNITIVE, Express::CONFUSING);
    $expr = Express::express($image, Xpress::COGNITIVE, Express::INTERESTING);
    $expr = Express::express($chessPlayer, Xpress::ELO, Express::GRANDMASTER);
    $expr = Express::express($chessPlayer, Xpress::ELO, 2832);
    $expr = Express::express($bill, Xpress::VOTE, Express::INFAVOR);
```

- retrieving expression values and stats for a specific model

## Creating Custom Types
- To create a Custom Expression Type, do the following:
    1. Add new Custom ExpressionType
        - Add record to ExpressionType Model via ExpressionTypes Seeder in DatabaseSeeder.php (or insert record into table)
        - Example: a custom type for expressing love
        ```
            ExpressionType::create([
                'id' => 11, 'description' => 'Love Rating from 1 to 5',
                'range_type' => 'int', 'min' => 1, 'max' => 5,
                'icons' => json_encode([    1 => 'icons/smallheart.png', 3 => 'icons/heart.png', 5 => 'icons/bigheart.png']),
                'labels' => json_encode([   1 => 'little love',  3 => 'love', 5 => 'looove' ])]);
        ```

    2. Add what models users can make custom type expressions to
        - Add records to ExpressableModel via ExpressableModelSeeder in DatabaseSeeder
        - Example: users can leave love expressions to the user and image models
        ```
            ExpressableModel::create([ 'id' => 4, 'expressable_type' => 'App\Models\User',   'expression_type_id' => 11 ]);
            ExpressableModel::create([ 'id' => 4, 'expressable_type' => 'App\Models\Image',  'expression_type_id' => 11 ]);
        ```

    3. Add constants for Facade Calls
        - Add constants, custom type name and expression values to Xpress.php
        ```
        const LOVERTG = 11;    // corresponds to id in expression_type_table

        const LTLLOVE = 1;
        const LOVE = 3;
        const LOOOVE = 5;
        ```

    4. Use Facades as above
        $expression = Express::express($image, Express::LOVERTG, Express::HUGELUV);
        $expression = Express::express($user,  Express::LOVERTG, Express::LUV);

- Testing
    - Testing environment is setup for Docker (SAIL) using SQLite DB in databases/test.sqlite

        ```
        sail artisan migrate --seed --env=testing
        sail artisan test
        ```

    - for local development, change the database absolute path in .env.testing; e.g. /home/user/src/laraexpress/databases/test.sqlite

        ```
        php artisan migrate  --seed --env=testing
        php artisan test
        ```

## Examples of Definition of Pre-defined Expression Types

- Michelin Stars
    - range_type: int
    - min: 1
    - max: 3
    - names: [ 1 => '1 Michelin Star', 2 => '2 Michelin Stars', 3 => '3 Michelin Stars' ]
    - icons: [ 1 => '1-star.png', 2 => '2-stars.png', 3 => '3-stars.png' ]

- Voting
    - range_type: int
    - min: 1
    - max: 2
    - names: [ 1 => 'In Favor', 2 => 'Against' ]
    - icons: null

- Hot-Cold
    - range_type: float
    - min: 1
    - max: 5
    - names: [ 1 => 'Freezing', 2 => 'Cold', 3 => 'Medium', 4 => 'Warm', 5 => 'Hot' ]
    - icons: [ 1 => 'freezing.png', 5 => 'hot.png' ]

## Design Choices
- Independence from rest of Laravel Application
    - i) facade call only (no use of eloquent)
    - ii) Use
        - If app is part of standalone Laravel project, use one Facade call only
        - If app is microservice, use rest routes only
    - PRO:
        - adding, removing, updating expressions is simpler in basic crud cases
        - updates / changes to expression versions can't affect rest of app
        - can create independent expressions microservice in architectures with different languages and frameworks
        - Integration with Relational or NoSQL Databases simple
        - Use of NoSQL DBS is simple; no changes to Facade
    - CON: can't integrate with eloquent models and queries
        - makes existing complex Eloquent queries in app a two/three-step process

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
