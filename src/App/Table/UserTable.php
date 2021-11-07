<?php declare(strict_types=1);

namespace App\Table;

use Administration\Table\AbstractTable;
use App\Model\User;
use Envms\FluentPDO\Query;

class UserTable extends AbstractTable
{
    public function __construct(Query $query)
    {
        parent::__construct($query, 'User');
    }

    public function insert(User $user): self
    {
        $values = [
            'roleId' => $user->getRoleId(),
            'name' => $user->getName(),
            'password' => $user->getPassword(),
            'email' => $user->getEmail(),
        ];

        $this->query->insertInto($this->table, $values)->execute();

        return $this;
    }

    public function findByName(string $name): bool|array
    {
        return $this->query->from($this->table)
            ->where('name', $name)
            ->fetch();
    }

    public function findByEMail(string $email): bool|array
    {
        return $this->query->from($this->table)
            ->where('email', $email)
            ->fetch();
    }
}
