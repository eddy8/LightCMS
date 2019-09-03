<?php

use Faker\Generator as Faker;

$factory->define(App\Model\Admin\Entity::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'table_name' => $faker->domainWord,
    ];
});
