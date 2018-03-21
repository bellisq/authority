<?php

namespace Bellisq\Authority\Tests\TestCases\Implementations;

use Bellisq\Authority\Implementations\PermissionContainer;
use PHPUnit\Framework\TestCase;


class ZZPermissionContainerTest
    extends TestCase
{
    public function testBehavior()
    {
        $authority = new PermissionContainer('Authority1', 'Authority2');
        $this->assertTrue($authority->sufficient('Authority1'));
        $this->assertTrue($authority->sufficient('Authority2'));
        $this->assertFalse($authority->sufficient('Authority3'));
    }
}