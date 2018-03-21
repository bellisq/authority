<?php

namespace Bellisq\Authority\Tests\TestCases\Implementations;

use Bellisq\Authority\Implementations\AggregateAuthority;
use Bellisq\Authority\Implementations\PermissionContainer;
use PHPUnit\Framework\TestCase;


class ZZAggregateAuthorityTest
    extends TestCase
{
    public function testBehavior()
    {
        $authority = new AggregateAuthority(
            null,
            new PermissionContainer('Authority1', 'Authority2'),
            new PermissionContainer('Authority3', 'Authority4'),
            new AggregateAuthority(
                null,
                new PermissionContainer('Authority5', 'Authority6'),
                new PermissionContainer('Authority7', 'Authority8')
            )
        );

        $this->assertTrue($authority->sufficient('Authority1'));
        $this->assertTrue($authority->sufficient('Authority2'));
        $this->assertTrue($authority->sufficient('Authority3'));
        $this->assertTrue($authority->sufficient('Authority4'));
        $this->assertTrue($authority->sufficient('Authority5'));
        $this->assertTrue($authority->sufficient('Authority6'));
        $this->assertTrue($authority->sufficient('Authority7'));
        $this->assertTrue($authority->sufficient('Authority8'));
        $this->assertFalse($authority->sufficient('Authority9'));
    }
}