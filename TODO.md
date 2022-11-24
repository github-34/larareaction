## TODO
- fE views
- service->obtaininfo shouldn't presuppose all objects of same model 
- add class (primary color, secondary) to five star
- // $table->unique([ 'user_id', 'expressable', 'expressable_id' ]);
- // $table->unique('expressable_type', 'expression_type_id');
- test icons/validate url path
- updaterequestform 
- validation in service->express()
- TESTing
    - Orechtrabench
    - Image migrations?
    - in docker (sail) environment:
        ```
        sail artisan migrate --seed --env=testing
        sail artisan test
        ```
    - in local development evironment:
        ```
        php artisan migrate  --seed --env=testing
        php artisan test
        ```
## Features: Implementation
- Eloquent Optionally?
- Mongo Compatibility
- Caching
- Optimization (Basic):
    - database indexes
    - STATS: don't check that the expressable exists & expressable-expressable_id exists every time stats is requested 
- Performance Testing:
    - large datasets & times
- Exception handling improvement: Custom Independent of Handler
- API Documentation: routes, return codes, exceptions
- Observers for Analytics
- Backward Compatibility for Laravel 7, 6, etc.
- Security
    - app, expressable, expressable_id shouldn't be visible to clients in AJAX calls; use hash, uuid (not really secure); encryption
    - app, expressable, expressable_id, user_id should not be visible inside the expressions table (in the microservice generally)

## Features: Use Cases
- Trending
- Viewings: add record of model viewed by specific users
    - why? more complex analytics based on model views and expressions;
    - why? full solution to viewer/expression and analytics
    - why? monitization solutions based on views and expressions becomes possible
- Analytics: Get User Community for expressable_Ids [e.g. what top 10 followers are expressing to a video; community ]
    - Store Owner (user_id) of Model Expressed to;
	    - E.g. Jones owns Photo: photo_id=3, user_id=3 ;
		- Mary expresses to photo:  expression_id: user_id=4, expressable_type="app\model\photo" expressable_id=3

- Guest expressions
- API Authentication: token, jwt
- expressions can be stored for different applications (for microservices)
- Admins can, in UI, create apps, api tokens, custom expression types, do deletes, force deletes

- Laravel Package

- Internationalization

- [User Groups and Model Authorization ] User Group X can (are authorized to) only express to Photos; User Group Y can express to Photos and Posts
    - Authorization: store in DB what models are expressable to by authenticated users?
    - [ user can express to others photo, but cannot express to post, even though both can be owned by a user; expressable can App\Models\Photo, not post ]


# Design Goals
- Decoupled / Independent of Rest of APP: why? Portability, MS architectures
    - Directory Structure
        - /App/expressions/Requests/* /App/expressions/Controller.php; App/expressions/Service.php
        - /App/expressions-Example/
    - Independent Exception Handling (no use of handler.php)
    - Independent Validation (FormRequest used)
    - No eloquent: no traits for models
    - no pivot tables: expressions, user_expressions
    - Compatibility with Relational or NOSQL Databases ease and no changes to
    - Optional: publishing eloquent traits, models, example

- Scalability & Performance
    - Compatibility with NoSQL DB: MongoDB, REDIS
    - Compatibility with REDIS alongside MYSQL
    - Comptaiblity with REDIS only
    - Use Lumen instead
    - use python instead????


    - Facades: design choice:
        - traits are not added to models that are "expressable"
        - no use of eloquent to get model expression (e.g. $user->expressions(), $image->expressions() ); instead, Facades are always used: expressions::getForModel($image), expressions::getForModel($user)
            - reason: decouples expressions code entirely from
                - at the same time give easy facade call to get same functionality.
            - con: may complicate or make less readable moderately complicated queriers (since cant assemble them with eloquent query builder)
            - con??: can't do eager/lazy loading
        - WHY IS Decoupling DONE?
            - within single application: simple uses of expressions are easy and readable with terse Facade syntax
            - the app can be offered as a microservice, in pretty much the same form, that only deals with expressions
