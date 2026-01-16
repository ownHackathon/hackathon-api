<?php declare(strict_types=1);

namespace UnitTest\Mock\Database;

use Envms\FluentPDO\Queries\Select;
use Envms\FluentPDO\Query;
use PDO;
use UnitTest\Mock\Constants\Account;
use UnitTest\Mock\Constants\AccountAccessAuth;

use function array_key_exists;

class MockSelect extends Select
{
    public function __construct(Query $fluent, string $from)
    {
        parent::__construct($fluent, $from);
    }

    public function fetch(?string $column = null, int $cursorOrientation = PDO::FETCH_ORI_NEXT): bool|array
    {
        if (array_key_exists('WHERE', $this->statements)) {
            return $this->handle($this->statements['FROM'], $this->statements['WHERE'], $this->parameters['WHERE']);
        }

        return [];
    }

    public function fetchAll($index = '', $selectOnly = ''): false|array
    {
        return match ($this->getFromTable()) {
            'Account' => [0 => Account::VALID_DATA],
            'AccountAccessAuth' => [0 => AccountAccessAuth::VALID_DATA],
            default => false
        };
    }

    private function handle(string $from, array $where, array $params): false|array
    {
        return match ($from) {
            'Account' => $this->handleAccount($where, $params),
            'AccountAccessAuth' => $this->handleAccountAccessAuth($where, $params),
            default => false
        };
    }

    private function handleAccount(array $where, array $params): false|array
    {
        if ($where[0][1] === 'id = ?' && $params[0] === Account::ID) {
            return Account::VALID_DATA;
        }

        if ($where[0][1] === 'uuid = ?' && $params[0] === Account::UUID) {
            return Account::VALID_DATA;
        }

        if ($where[0][1] === 'name = ?' && $params[0] === Account::NAME) {
            return Account::VALID_DATA;
        }

        if ($where[0][1] === 'email = ?' && $params[0] === Account::EMAIL) {
            return Account::VALID_DATA;
        }

        return false;
    }

    private function handleAccountAccessAuth(array $where, array $params): false|array
    {
        if ($where[0][1] === 'id = ?' && $params[0] === AccountAccessAuth::ID) {
            return AccountAccessAuth::VALID_DATA;
        }

        if ($where[0][1] === 'userId = ?' && $params[0] === AccountAccessAuth::USER_ID) {
            return [0 => AccountAccessAuth::VALID_DATA];
        }

        if ($where[0][1] === 'label = ?' && $params[0] === AccountAccessAuth::LABEL) {
            return [0 => AccountAccessAuth::VALID_DATA];
        }

        if ($where[0][1] === 'refreshToken = ?' && $params[0] === AccountAccessAuth::REFRESH_TOKEN) {
            return AccountAccessAuth::VALID_DATA;
        }

        if ($where[0][1] === 'userAgent = ?' && $params[0] === AccountAccessAuth::USER_AGENT) {
            return [0 => AccountAccessAuth::VALID_DATA];
        }

        if ($where[0][1] === 'clientIdentHash = ?' && $params[0] === AccountAccessAuth::CLIENT_IDENT_HASH) {
            return AccountAccessAuth::VALID_DATA;
        }

        return false;
    }
}
