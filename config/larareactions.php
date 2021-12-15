<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Microservice
    |--------------------------------------------------------------------------
    |
    | This option defines whether the app is a microservice or part of a laravel
    | application.
    |
    | If app is a microservice, the following architectural assumptions are made
    |       1. Requests are authenticated based on local user table
    |       2. Reactions are user-authorized
    |           a. a user can only update his reaction
    |           b. a user can store a reaction only for models that are reactable
    |       3. Requests are **assumed** to receive valid reactable_id
    |       4. Requests are **assumed** to received Valid <reactable_id, reactable_type> pairs
    |
    | If app is standalone Laravel, the following assumptions are made
    |       1. User_id as Owners of Reactions are validated
    |       2. Reactable_ids are validated as belonging to Reactable Models
    */
    'microservice' => env('APP_MICROSERVICE', 'true'),

    /*
    |--------------------------------------------------------------------------
    | Reactable Models
    |--------------------------------------------------------------------------
    |
    | Models that can be reacted to along with their reaction types.
    | The reaction type id is the id from the reaction_types DB table.
    |
    */
    'reactableModels' => [
        [   'reactable_type' => 'App\Models\Image',
            'reaction_type_id' => 2
       ],
        [   'reactable_type' => 'App\Models\User',
            'reaction_type_id' => 2
        ]
    ],

];
