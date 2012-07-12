<?php

namespace WootookTest\Core\Database\Sql\Dml\Section;

use Wootook\Core\Database\Sql\Dml,
    Wootook\Core\Database\Sql\Dml\Section;

abstract class WhereMock
    extends Dml\DmlQuery
    implements Section\WhereAware
{
    use Section\Where;
}
