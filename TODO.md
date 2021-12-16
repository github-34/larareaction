# Todo
- reaction type constants for facades calls
- Sample Blade UI (Star Rating)

## Improvements
- use eloquent between the reaction, reaction type models

## Features: Implementation
- Service Provider
- Facades for Getting User Reactions, ModelId Reactions, ModelId Reaction Stats [need Service Provider First]
- Mongo Compatibility
- Caching
- Optimization (Basic):
    - database indexes
    - STATS: don't check that the reactable exists & reactable-reactable_id exists every time stats is requested 
        [implement caching first???]
- Testing
    - testing environment: separate db service in docker for testing;
    - remove ids from seeders; user factories
    - large datasets & times
- Exception handling Independent of Handler
- API Documentation: routes, return codes, exceptions
- Observers for Analytics
- Authentication: token, JWT in addition to session/cookie
- Backward Compatibility for Laravel 7, 6, etc.
## Features: Use Cases
- Trending
- Viewings: add record of model viewed by specific users
    - why? more complex analytics based on model views and reactions;
    - why? full solution to viewer/reaction and analytics
    - why? monitazation solutions based on views and reactions becomes possible
- Analytics: Get User Community for Reactable_Ids [e.g. what top 10 followers are reacting to a video; community ]
    - Store Owner (user_id) of Model Reacted to;
	    - E.g. Jones owns Photo: photo_id=3, user_id=3 ;
		- Mary reacts to photo:  reaction_id: user_id=4, reactable_type="app\model\photo" reactable_id=3

- Guest Reactions

- Multiple Reaction Types for each Model

- API Authentication: token, jwt
- Reactions can be stored for different applications (for microservices)
- Admins can, in UI, create apps, api tokens, custom reaction types, do deletes, force deletes
- Security
    - app, reactable, reactable_id shouldn't be visible to clients in AJAX calls; use hash, uuid (not really secure); encryption
    - app, reactable, reactable_id, user_id should not be visible inside the reactions table (in the microservice generally)

- Laravel Package

- Internationalization

- [User Groups and Model Authorization ] User Group X can (are authorized to) only react to Photos; User Group Y can react to Photos and Posts
    - Authorization: store in DB what models are reactable to by authenticated users?
    - [ user can react to others photo, but cannot react to post, even though both can be owned by a user; reactable can App\Models\Photo, not post ]

- Optional Implementation with Eloquent Models
    - Publish publishes traits, models and eloquent examples if specified in config/env

# Backburner
- Confusing Naming: ReactonTypes (model) vs reactable_type: ReactionKinds?????
- Do we need precision values on float range reaction types??? [Nah]
- Do we need ReactionTypes with name/value ranges specified as lower AND upper bound of range?
    - Right now, the, say, lowest value can be used to specify the range: 2200 for Grandmaster (2200-2499), 2500 International Grandmaster (2500-2699), 2700 Super Grandmaster (2700+)


# Design Goals
- Decoupled / Independent of Rest of APP: why? Portability, MS architectures
    - Directory Structure
        - /App/Reactions/Requests/* /App/Reactions/Controller.php; App/Reactions/Service.php
        - /App/Reactions-Example/
    - Independent Exception Handling (no use of handler.php)
    - Independent Validation (FormRequest used)
    - No eloquent: no traits for models
    - no pivot tables: reactions, user_reactions
    - Compatibility with Relational or NOSQL Databases ease and no changes to
    - Optional: publishing eloquent traits, models, example

- Scalability & Performance
    - Compatibility with NoSQL DB: MongoDB, REDIS
    - Compatibility with REDIS alongside MYSQL
    - Comptaiblity with REDIS only
    - Use Lumen instead
    - use python instead????


    - Facades: design choice:
        - traits are not added to models that are "reactable"
        - no use of eloquent to get model reaction (e.g. $user->reactions(), $image->reactions() ); instead, Facades are always used: Reactions::getForModel($image), Reactions::getForModel($user)
            - reason: decouples reactions code entirely from
                - at the same time give easy facade call to get same functionality.
            - con: may complicate or make less readable moderately complicated queriers (since cant assemble them with eloquent query builder)
            - con??: can't do eager/lazy loading
        - WHY IS Decoupling DONE?
            - within single application: simple uses of reactions are easy and readable with terse Facade syntax
            - the app can be offered as a microservice, in pretty much the same form, that only deals with reactions
## Design Choices
- Independence from rest of Laravel Application; [complete independence???]
    - i) no interaction with eloquent [facade call only]
    - ii) Use
        - If app is part of standalone Laravel project, use one Facade call only
        - If app is microservice, use rest routes only
    - PRO:
        - adding, removing, updating reactions is simpler in basic crud cases
        - updates / changes to reactions versions can't affect rest of app
        - can create independent reactions microservice in architectures with different languages and frameworks
        - Integration with Relational or NoSQL Databases simple
        - Use of NoSQL DBS is simple; no changes to Facade
    - CON: can't integrate with eloquent models and queries
        - makes existing complex Eloquent queries in app a two/three-step process


<!--
public function storeGuestReaction(array $validatedInputInput)
    {
        //     // find extant reaction [in session]
        //     $reaction = null;
        //     // if there is an extant reaction for reactable and submitted reaction is different then update it
        //     // if there is no extant reaction, create it [in session]
        //     if ($reaction == null) {
        //         $reaction = Reaction::create($validatedInput);
        //         $request->session()->push("reactions", $reaction);
        //     }
    }

HTTP or API Responses
        // return $request->is('api/*') ? $reaction : back()->with('status', 'Rating updated!'); //->withErrors([ 'email' => 'The provided credentials do not match our records.' ]);



        // // Check if reactable_type class exists
                // if (!class_exists($data['reactable_type'])) {
                //     $validator->errors()->add('reactable_type', 'cannot make reaction to reactable type');
                //     return;
                // }

                // // Check if the input reactable model is reactable i.e. uses Reactable Trait
                // $reflect = new \ReflectionClass($data['reactable_type']);
                // $traits = $reflect->getTraits();
                // if (!array_key_exists('App\Traits\Reactable', $traits)) {
                //     $validator->errors()->add('reactable_type', 'Cannot React to Selected Model');
                //     return;
                // } -->

<!-- 
Eloquent Additions

Image
uses Reactable
User
    public function reactions();
<!--
<!--
    <?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Config;

trait Reactable
{
    public function reactionStats();
    public function reactions() {
        return $this->morphMany(Reaction::class, 'reactable');
    }
    public function reaction(?User $user) {
        return Auth::user() ?? Reaction::where('user_id', '=', Auth::user()->id)->where('reactable_id', '=', $this->id)->first();
    }
    public function approvedreactions() {
        return $this->morphMany(Config::get('larareact.model'), 'reactable')->where('approved', true);
    }
}

Reaction
public function user() {
    return $this->morphTo();
}
public function reactable() {
    return $this->morphTo();
}

User
public function images() {
    return $this->hasMany(Image::class);
}



Config
'default_lang' => 'en',
'model' => \App\Models\Reaction::class,
'controller' => '\App\Http\Controllers\ReactionController',

'permissions' => [
    'create-rating' => 'App\Policies\RatingPolicy@create',
    'delete-rating' => 'App\Policies\RatingPolicy@delete',
    'edit-rating' => 'App\Policies\RatingPolicy@update',
],
'guest_reactions_allowed' => true,
'routes' => true,
'api-routes' => false,
'soft_deletes' => true,
'load_migrations' => true, --> --> -->

