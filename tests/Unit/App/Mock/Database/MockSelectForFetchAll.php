<?php declare(strict_types=1);

namespace Test\Unit\App\Mock\Database;

use Envms\FluentPDO\Queries\Select;
use Envms\FluentPDO\Query;
use PDO;
use Test\Unit\App\Mock\TestConstants;

use function array_key_exists;

class MockSelectForFetchAll extends Select
{
    public function __construct(Query $fluent, string $from)
    {
        parent::__construct($fluent, $from);
    }

    public function fetch(?string $column = null, int $cursorOrientation = PDO::FETCH_ORI_NEXT): bool|array
    {
        if (array_key_exists(1, $this->statements['SELECT'])
            && $this->statements['SELECT'][1] === 'COUNT(id) AS countTopic'
        ) {
            return [
                'countTopic' => 1,
            ];
        }

        if (array_key_exists('WHERE', $this->statements)) {
            return $this->handle($this->statements['FROM'], $this->statements['WHERE'], $this->parameters['WHERE']);
        }

        return false;
    }

    public function fetchAll($index = '', $selectOnly = ''): array
    {
        return [];
    }

    private function handle(string $from, array $where, array $params): bool|array
    {
        return match ($from) {
            'Event' => $this->handleEvent($where, $params),
            'Participant' => $this->handleParticipant($where, $params),
            'Project' => $this->handleProject($where, $params),
            'TopicPool' => $this->handleTopic($where, $params),
            'User' => $this->handleUser($where, $params),
            default => false
        };
    }

    private function handleEvent(array $where, array $params): bool|array
    {
        if ($where[0][1] === 'title = ?' && $params[0] === TestConstants::EVENT_TITLE) {
            return ['id' => 1];
        }

        if ($where[0][1] === 'id = ?' && $params[0] === TestConstants::EVENT_ID) {
            return ['id' => 1];
        }

        return false;
    }

    private function handleParticipant(array $where, array $params): bool|array
    {
        if ($where[0][1] === 'id = ?' && $params[0] === TestConstants::PARTICIPANT_ID) {
            return ['id' => 1];
        }

        if ($where[0][1] === 'userId = ?' && $params[0] === TestConstants::USER_ID) {
            return ['id' => 1];
        }

        return false;
    }

    private function handleProject(array $where, array $params): bool|array
    {
        if ($where[0][1] === 'id = ?' && $params[0] === TestConstants::PROJECT_ID) {
            return ['id' => 1];
        }

        if ($where[0][1] === 'participantId = ?' && $params[0] === TestConstants::PARTICIPANT_ID) {
            return ['id' => 1];
        }

        return false;
    }

    private function handleTopic(array $where, array $params): bool|array
    {
        if ($where[0][1] === 'id = ?' && $params[0] === TestConstants::TOPIC_ID) {
            return ['id' => 1];
        }

        if ($where[0][1] === 'uuid = ?' && $params[0] === TestConstants::TOPIC_UUID) {
            return ['id' => 1];
        }

        if ($where[0][1] === 'eventId = ?' && $params[0] === TestConstants::EVENT_ID) {
            return ['id' => 1];
        }

        if ($where[0][1] === 'topic = ?' && $params[0] === TestConstants::TOPIC_TITLE) {
            return ['id' => 1];
        }

        return false;
    }

    private function handleUser(array $where, array $params): bool|array
    {
        if ($where[0][1] === 'id = ?' && $params[0] === TestConstants::USER_ID) {
            return ['id' => 1];
        }

        if ($where[0][1] === 'uuid = ?' && $params[0] === TestConstants::USER_UUID) {
            return ['id' => 1];
        }

        if ($where[0][1] === 'name = ?' && $params[0] === TestConstants::USER_NAME) {
            return ['id' => 1];
        }

        if ($where[0][1] === 'email = ?' && $params[0] === TestConstants::USER_EMAIL) {
            return ['id' => 1];
        }

        if ($where[0][1] === 'token = ?' && $params[0] === TestConstants::USER_TOKEN) {
            return ['id' => 1];
        }

        return false;
    }
}
