<?php

namespace Baum\Tests\Baseline;

use PHPUnit\Framework\TestCase;

class BaselineTest extends TestCase
{
    use MyTrait;

    public function testTrueIsTrue()
    {
        $this->assertTrue(true);
    }

    public function testTrait()
    {
        $this->assertTrue($this->stub());
    }
}
