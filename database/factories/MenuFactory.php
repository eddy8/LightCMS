<?php

use Faker\Generator as Faker;

$factory->define(App\Model\Admin\Menu::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'route' => str_random(10)
    ];
});
