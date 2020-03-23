<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Ticket;
use Faker\Generator as Faker;

$factory->define(Ticket::class, function (Faker $faker) {
    return [
        'code' => 'AST-'.$faker->unique()->randomNumber($nbDigits = 7, $strict = false) .'-'. $faker->word .'-'. $faker->randomDigit,
        'user_id' => factory(\App\User::class),
        'title' => $faker->word,
        'importance' => $faker->word,
        'issue_date' => $faker->dateTime($max='now', $timezone=null),
        // 'ticket_handler' => $faker->randomDigit,
        'description' => $faker->sentence($nbWords = 10, $variableNbWords = true)
    ];
});
