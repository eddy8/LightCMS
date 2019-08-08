<?php

use Faker\Generator as Faker;

$factory->define(App\Model\Admin\AdminUser::class, function (Faker $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
        'status' => 1
    ];
});
