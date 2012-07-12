<?php

namespace WootookUnit\Core\Database\Sql\Dml\Section;

use Wootook\Core\Database\Sql\Dml,
    Wootook\Core\Database\Sql\Dml\Section;

abstract class IntoMock
    extends Dml\DmlQuery
    implements Section\IntoAware
{
    use Section\Into;
}
