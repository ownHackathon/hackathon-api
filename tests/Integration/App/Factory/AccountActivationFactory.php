<?php declare(strict_types=1);

namespace Tests\Integration\App\Factory;

use DateTimeImmutable;
use Envms\FluentPDO\Query;
use Exdrals\Core\Shared\Domain\Enum\DateTimeFormat;
use Exdrals\Core\Shared\Utils\UuidFactoryInterface;
use Faker\Factory as Faker;

class AccountActivationFactory
{
    public static function create(array $attributes = []): array
    {
        $faker = Faker::create('de_DE');
        $container = test()->getContainer();
        $fluent = $container->get(Query::class);
        $uuid = $container->get(UuidFactoryInterface::class);

        $defaults = [
            'email' => $faker->unique()->safeEmail(),
            'token' => $uuid->uuid7()->toString(),
            'createdAt' => new DateTimeImmutable()->format(DateTimeFormat::DEFAULT->value),
        ];

        $data = array_merge($defaults, $attributes);

        $id = $fluent->insertInto('AccountActivation', $data)->execute();
        $data['id'] = $id;

        return $data;
    }
}
