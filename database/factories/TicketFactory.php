<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Ticket;
use Faker\Generator as Faker;

$factory->define(Ticket::class, function (Faker $faker) {
    return [
        'code' => 'AST-20200319020400-HIGH-1',
        'user_id' => factory(\App\User::class),
        'title' => $faker->word,
        'importance' => $faker->word,
        'issue_date' => $faker->dateTime($max='now', $timezone=null),
        // 'ticket_handler' => $faker->randomDigit,
        'description' => $faker->sentence($nbWords = 10, $variableNbWords = true)
    ];
});
