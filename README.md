

## Larareaction

A simple way of handling reactions or ratings in Laravel.

## Features

- Reactions
    - Reaction types = Applause; Like-Dislike; 1-5 Stars; etc.
    - Reactable models (models that can be reacted to) are predefined
    - One user reaction per reactable model
    - One reaction type per reaction and model (e.g. cannot have like/dislike for Photos and star rating)
    - Storing an existing reaction overwrites it

    - Only authenticated users can leave reactions
    - Users can only update or delete their own reactions
    - Users can only react to a predefined set of reactable models

- Reaction types
    - defined by range_type (int, float), min, max values

- Built in types
    - Applause
    - Like/Dislike


## Usage

    Larareact::react('App\Models\Image', 2, Const::LIKE);
    Larareact::react(get_class($this), 2, Const::LIKE);

## Examples of Definition of Custom Types

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

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
