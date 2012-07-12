<?php

namespace WootookUnit\Core\Database\Sql\Dml\Section;

use Wootook\Core\Database\Sql\Dml,
    Wootook\Core\Database\Sql\Dml\Section;

abstract class FromMock
    extends Dml\DmlQuery
    implements Section\FromAware
{
    use Section\From;
}
