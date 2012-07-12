<?php

namespace WootookTest\Core\Database\Sql\Dml\Section;

use Wootook\Core\Database\Sql\Dml,
    Wootook\Core\Database\Sql\Dml\Section;

abstract class SetMock
    extends Dml\DmlQuery
    implements Section\SetAware
{
    use Section\Set;
}
