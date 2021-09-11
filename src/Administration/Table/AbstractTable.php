<?php declare(strict_types=1);

namespace Administration\Table;

use Envms\FluentPDO\Query;

class AbstractTable
{
    public function __construct(
        protected Query $query
    ) {
    }
}
