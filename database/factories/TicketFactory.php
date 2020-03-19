<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Ticket;
use Faker\Generator as Faker;

$factory->define(Ticket::class, function (Faker $faker) {
    return [
        'code' => $faker->regexify('[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}'),
        'user_id' => factory(\App\User::class),
        'importance' => $faker->word,
        // 'ticket_handler' => $faker->randomDigit,
        'description' => $faker->sentence($nbWords = 10, $variableNbWords = true)
    ];
});
