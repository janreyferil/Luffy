<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'first' => $faker->firstName($gender = 'male'|'female'),
        'last' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'role' => 'user',
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Status::class, function (Faker $faker) {
   return [
    'user_id' => function () {
        return App\User::all()->random();
    }
    ];
});

$factory->define(App\Report::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return App\User::all()->random();
        },
        'user_to_id' => function () {
            return App\User::all()->random();
        },
        'report' =>  $faker->sentence,
        'reason' =>  $faker->sentence,
        'level' => $faker->numberBetween(1,5)
    ];
});

$factory->define(App\Post::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return App\User::all()->random();
        },
        'title' => $faker->sentence,
        'body' => $faker->paragraph
    ];
});

$factory->define(App\PostComment::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return App\User::all()->random();
        },
        'post_id' => function () {
            return App\Post::all()->random();
        },
        'body' => $faker->paragraph
    ];
});

$factory->define(App\PostReact::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return App\User::all()->random();
        },
        'post_id' => function () {
            return App\Post::all()->random();
        }
    ];
});

$factory->define(App\CommentReact::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return App\User::all()->random();
        },
        'comment_id' => function () {
            return App\Post::all()->random();
        }
    ];
});

