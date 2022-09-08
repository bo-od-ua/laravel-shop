<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Category;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Category::class, function (Faker $faker) {
    $name = $faker->realText(rand(30, 40));
    return [
        'name'=> $name,
        'content'=> $faker->realText(rand(150, 200)),
        'slug'=> Str::slug($name),
    ];
});
