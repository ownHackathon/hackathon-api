<?php declare(strict_types=1);

namespace Test\Unit\Mock\Database;

use Envms\FluentPDO\Queries\Select;
use Envms\FluentPDO\Query;
use PDO;
use Test\Unit\Mock\TestConstants;

use function array_key_exists;

class MockSelect extends Select
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
        return [
            0 => ['id' => 1],
        ];
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
        return match ($where[0][1]) {
            'id = ?' => $params[0] === TestConstants::EVENT_ID ? ['id' => TestConstants::EVENT_ID] : false,
            'title = ?' => $params[0] === TestConstants::EVENT_TITLE ? ['id' => TestConstants::EVENT_ID] : false,
            default => false
        };
    }

    private function handleParticipant(array $where, array $params): bool|array
    {
        return match ($where[0][1]) {
            'id = ?' => $params[0] === TestConstants::PARTICIPANT_ID ? ['id' => TestConstants::PARTICIPANT_ID] : false,
            'userId = ?' => $params[0] === TestConstants::USER_ID ? ['id' => TestConstants::PARTICIPANT_ID] : false,
            default => false
        };
    }

    private function handleProject(array $where, array $params): bool|array
    {
        return match ($where[0][1]) {
            'id = ?' => $params[0] === TestConstants::PROJECT_ID ? ['id' => TestConstants::PROJECT_ID] : false,
            'participantId = ?' =>
            $params[0] === TestConstants::PARTICIPANT_ID ? ['id' => TestConstants::PROJECT_ID] : false,
            default => false
        };
    }

    private function handleTopic(array $where, array $params): bool|array
    {
        return match ($where[0][1]) {
            'id = ?' => $params[0] === TestConstants::TOPIC_ID ? ['id' => TestConstants::TOPIC_ID] : false,
            'uuid = ?' => $params[0] === TestConstants::TOPIC_UUID ? ['id' => TestConstants::TOPIC_ID] : false,
            'eventId = ?' => $params[0] === TestConstants::EVENT_ID ? ['id' => TestConstants::TOPIC_ID] : false,
            'topic = ?' => $params[0] === TestConstants::TOPIC_TITLE ? ['id' => TestConstants::TOPIC_ID] : false,
            default => false
        };
    }

    private function handleUser(array $where, array $params): bool|array
    {
        return match ($where[0][1]) {
            'id = ?' => $params[0] === TestConstants::USER_ID ? ['id' => TestConstants::USER_ID] : false,
            'uuid = ?' => $params[0] === TestConstants::USER_UUID ? ['id' => TestConstants::USER_ID] : false,
            'name = ?' => $params[0] === TestConstants::USER_NAME ? ['id' => TestConstants::USER_ID] : false,
            'email = ?' => $params[0] === TestConstants::USER_EMAIL ? ['id' => TestConstants::USER_ID] : false,
            'token = ?' => $params[0] === TestConstants::USER_TOKEN ? ['id' => TestConstants::USER_ID] : false,
            default => false,
        };
    }
}
