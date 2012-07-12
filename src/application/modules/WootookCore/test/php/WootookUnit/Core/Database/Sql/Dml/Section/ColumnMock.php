<?php

namespace WootookUnit\Core\Database\Sql\Dml\Section;

use Wootook\Core\Database\Sql\Dml,
    Wootook\Core\Database\Sql\Dml\Section;

abstract class ColumnMock
    extends Dml\DmlQuery
    implements Section\ColumnAware
{
    use Section\Column;
}
