<?php

namespace WootookUnit\Core\Database\Sql\Dml\Section\Renderer;

use Wootook\Core\Database\Sql\Dml,
    Wootook\Core\Database\Sql\Dml\Section\Renderer;

abstract class SetMock
    implements Renderer\SetAware
{
    use Renderer\Set;
}
