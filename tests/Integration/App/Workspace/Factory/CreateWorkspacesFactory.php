<?php declare(strict_types=1);

namespace Tests\Integration\App\Workspace\Factory;

use Envms\FluentPDO\Query;
use Exdrals\Shared\Infrastructure\Service\SlugService;
use Faker\Factory as Faker;
use Tests\Integration\App\Factory\AccountFactory;

class CreateWorkspacesFactory
{
    public static function create(array $attributes = []): array
    {
        $faker = Faker::create('de_DE');

        $container = test()->getContainer();
        $slugService = $container->get(SlugService::class);
        $fluent = $container->get(Query::class);

        $account['accountId'] = $attributes['accountId'] ?? null;
        $account['uuid'] = $attributes['uuid'] ?? null;
        unset($attributes['uuid']);

        if ($account['accountId'] === null) {
            $account = AccountFactory::create();
        }
        $name = $faker->regexify('[A-Za-z0-9][A-Za-z0-9 _-]{10,30}');
        $slug = $slugService->getSlugFromString($name);
        $defaults = [
            'uuid' => $faker->unique()->uuid(),
            'accountId' => $account['accountId'],
            'name' => $name,
            'slug' => $slug,
            'description' => $faker->text(50),
        ];

        $data = array_merge($defaults, $attributes);

        $id = $fluent->insertInto('Workspace', $data)->execute();
        $data['id'] = $id;
        $data['ownerUuid'] = $account['uuid'];

        return $data;
    }

    public static function createManyForOneAccount(int $count, array $attributes = []): array
    {

        if (!array_key_exists('accountId', $attributes)) {
            $account = AccountFactory::create();
            $attributes['accountId'] = $account['id'];
        }

        $workspaces = [];
        for ($i = 0; $i < $count; $i++) {
            $workspaces[] = self::create($attributes);
        }
        return $workspaces;
    }
}
