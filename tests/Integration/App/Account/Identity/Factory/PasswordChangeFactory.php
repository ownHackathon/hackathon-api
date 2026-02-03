<?php declare(strict_types=1);

namespace Tests\Integration\App\Account\Identity\Factory;

use Envms\FluentPDO\Query;
use Exdrals\Shared\Domain\Enum\Token\TokenType;
use Faker\Factory as Faker;

readonly class PasswordChangeFactory
{
    public static function create(array $attributes = []): array
    {
        $faker = Faker::create('de_DE');

        $container = test()->getContainer();
        $fluent = $container->get(Query::class);

        $account = AccountFactory::create();

        $defaults = [
            'accountId' => $account['id'],
            'token' => $faker->unique()->uuid(),
            'tokenType' => TokenType::EMail->value,
        ];

        $data = array_merge($defaults, $attributes);

        $id = $fluent->insertInto('Token', $data)->execute();
        $data['id'] = $id;

        return $data;
    }
}
