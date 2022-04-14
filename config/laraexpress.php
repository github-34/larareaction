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
    |       2. Expressions are user-authorized
    |           a. a user can only update his expression
    |           b. a user can store a expression only for models that are expressable
    |       3. Requests are **assumed** to receive valid expressable_id
    |       4. Requests are **assumed** to received Valid <expressable_id, expressable_type> pairs
    |
    | If app is standalone Laravel, the following assumptions are made
    |       1. User_id as Owners of Expressions are validated
    |       2. Expressable_ids are validated as belonging to Expressable Models
    */
    'microservice' => env('APP_MICROSERVICE', 'true'),
];
