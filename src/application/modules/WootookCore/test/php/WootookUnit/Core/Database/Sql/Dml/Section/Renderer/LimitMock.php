<?php

namespace WootookUnit\Core\Database\Sql\Dml\Section\Renderer;

use Wootook\Core\Database\Sql\Dml,
    Wootook\Core\Database\Sql\Dml\Section\Renderer;

abstract class LimitMock
    implements Renderer\LimitAware
{
    use Renderer\Limit;
}
