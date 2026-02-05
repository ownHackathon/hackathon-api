<?php declare(strict_types=1);

namespace Tests\Integration\App\Factory;

use Envms\FluentPDO\Query;
use Faker\Factory as Faker;

class AccountFactory
{
    public static function create(array $attributes = []): array
    {
        $faker = Faker::create('de_DE');

        $container = test()->getContainer();
        $fluent = $container->get(Query::class);
        $password = $faker->password(8);
        $defaults = [
            'uuid' => $faker->unique()->uuid(),
            'name' => $faker->unique()->name(),
            'email' => $faker->unique()->safeEmail(),
            'password' => password_hash($password, PASSWORD_BCRYPT),
        ];

        $data = array_merge($defaults, $attributes);

        $id = $fluent->insertInto('Account', $data)->execute();
        $data['id'] = $id;
        $data['password'] = $password;

        return $data;
    }

    public static function createMany(int $count, array $attributes = []): array
    {
        $accounts = [];
        for ($i = 0; $i < $count; $i++) {
            $accounts[] = self::create($attributes);
        }
        return $accounts;
    }
}
