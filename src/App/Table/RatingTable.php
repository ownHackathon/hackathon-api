<?php
declare(strict_types=1);

namespace App\Table;

use Administration\Table\AbstractTable;
use Envms\FluentPDO\Query;

class RatingTable extends AbstractTable
{
    public function __construct(Query $query)
    {
        parent::__construct($query, 'Rating');
    }
}
