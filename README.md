


<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>
- [Simple, fast routing engine](https://laravel.com/docs/routing).
- **[Romega Software](https://romegasoftware.com)**

## Larareaction

A simple way of handling reactions or ratings in Laravel.

## Features

- Reactions
    - One user reaction per model (and additional store requests are updates)
    - One Reaction Type per Reaction and Model (e.g. cannot have like/dislike for Photos and star rating)

- Reaction Types
    - Built in Types
        - Applause
        - Like /Dislike
        ...
    - Custom Types: min, max, discrete (integer)/non-discrete (float), icons and any names associated with values in range

- Authorization
    - only authenticated users can leave reactions
    - users can only update or delete their own reaction

- Validation
    - users can only react to a predefined set of Reactable Models (see config file)
    - reactions must be "numeric"
    - reactable_types must be strings

- Tests
    - ZZZZ


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
        - Integration with Relational or NOSQL Databases simple
        - Use of NOSQL DBS is simple; no changes to Facade
    - CON: can't integrate with eloquent models and queries
        - makes existing complex Eloquent queries in app a two/three-step process

- Reaction Types
    - defined by minimum range values, integer/float: is this a problem in some cases?
    - developers can create any custom reaction type (as long as int/float, (optionally) have names, reactable): Is this a problem? Does it need to be constrained further?

- Validation: stats request use form parameters
    - should it be POST with JSON body or GET request with uri parameters
    - REST Api best practices??

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
