<?php

namespace WootookTest\Core\Database\Sql\Dml\Section\Renderer;

use Wootook\Core\Database\Sql\Dml,
    Wootook\Core\Database\Sql\Dml\Section\Renderer;

abstract class ColumnMock
    implements Renderer\FromAware
{
    use Renderer\Column;
}
