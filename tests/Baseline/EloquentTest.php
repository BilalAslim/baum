<?php

namespace Baum\Tests\Baseline;

use Baum\Tests\Baseline\Models\BaseLineAlpha;

class EloquentTest extends UnitAbstract
{
    public function testRecordCount()
    {
        factory(BaseLineAlpha::class, 50)->create();
        $this->assertEquals(BaseLineAlpha::count(), 50);
    }
}
