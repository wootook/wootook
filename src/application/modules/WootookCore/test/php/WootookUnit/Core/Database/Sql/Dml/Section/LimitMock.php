<?php

namespace WootookUnit\Core\Database\Sql\Dml\Section;

use Wootook\Core\Database\Sql\Dml,
    Wootook\Core\Database\Sql\Dml\Section;

abstract class LimitMock
    extends Dml\DmlQuery
    implements Section\LimitAware
{
    use Section\Limit;
}
