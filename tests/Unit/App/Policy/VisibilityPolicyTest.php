<?php declare(strict_types=1);

namespace Tests\Unit\App\Policy;

use App\Account\Identity\Api\DTO\Account\AccountProfile;
use App\Policy\Api\Enum\Visibility;
use App\Policy\Api\VisibilityAwareInterface;
use App\Policy\Application\VisibilityPolicy;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid;

use function expect;
use function test;

readonly class StubVisibilityElement implements VisibilityAwareInterface
{
    public function __construct(
        public Visibility $visibility,
        private ?int $ownerId,
    ) {
    }

    public function getOwnerId(): ?int
    {
        return $this->ownerId;
    }
}

class VisibilityStubAccountBuilder
{
    public static function build(?int $id): AccountProfile
    {
        return new AccountProfile(
            $id,
            Uuid::uuid4(),
            'Alice',
        );
    }
}

test('public elements are available for everyone', function (): void {
    $policy = new VisibilityPolicy();
    $element = new StubVisibilityElement(Visibility::PUBLIC, 42);

    expect($policy->isAvailableFor($element, null))->toBeTrue()
        ->and($policy->isAvailableFor($element, VisibilityStubAccountBuilder::build(42)))->toBeTrue()
        ->and($policy->isAvailableFor($element, VisibilityStubAccountBuilder::build(99)))->toBeTrue();
});

test('registered elements are available only for authenticated accounts', function (): void {
    $policy = new VisibilityPolicy();
    $element = new StubVisibilityElement(Visibility::REGISTERED, 42);

    expect($policy->isAvailableFor($element, null))->toBeFalse()
        ->and($policy->isAvailableFor($element, VisibilityStubAccountBuilder::build(42)))->toBeTrue()
        ->and($policy->isAvailableFor($element, VisibilityStubAccountBuilder::build(99)))->toBeTrue();
});

test('unlisted elements are available only for their owner', function (): void {
    $policy = new VisibilityPolicy();
    $element = new StubVisibilityElement(Visibility::UNLISTED, 42);

    expect($policy->isAvailableFor($element, null))->toBeFalse()
        ->and($policy->isAvailableFor($element, VisibilityStubAccountBuilder::build(42)))->toBeTrue()
        ->and($policy->isAvailableFor($element, VisibilityStubAccountBuilder::build(99)))->toBeFalse();
});

test('workspace exposes its account as owner', function (): void {
    $workspace = new \App\Workspace\Domain\Workspace(
        1,
        Uuid::uuid4(),
        42,
        'Name',
        'name',
        null,
        null,
        Visibility::PUBLIC,
        new DateTimeImmutable(),
        new DateTimeImmutable(),
    );

    expect($workspace->getOwnerId())->toBe(42);
});
