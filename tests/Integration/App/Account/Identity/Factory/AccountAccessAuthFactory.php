<?php declare(strict_types=1);

namespace Tests\Integration\App\Account\Identity\Factory;

use DateTimeImmutable;
use Envms\FluentPDO\Query;
use Faker\Factory as Faker;
use Core\Clock\DateTimeFormat;

class AccountAccessAuthFactory
{
    public static function create(array $attributes = []): array
    {
        $container = test()->getContainer();
        $fluent = $container->get(Query::class);
        $defaults = [
            'accountId' => $attributes['accountId'] ?: Faker::create()->numberBetween(),
            'label' => 'default',
            'refreshToken' => Faker::create()->uuid(),
            'userAgent' => 'unknown',
            'clientIdentHash' => Faker::create()->uuid(),
            'createdAt' => new DateTimeImmutable()->format(DateTimeFormat::DEFAULT->value),
        ];

        $data = array_merge($defaults, $attributes);

        $id = $fluent->insertInto('AccountAccessAuth', $data)->execute();

        $data['id'] = $id;

        return $data;
    }
}
