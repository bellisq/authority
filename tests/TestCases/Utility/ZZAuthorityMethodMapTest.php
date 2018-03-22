<?php

namespace Bellisq\Authority\Tests\TestCases\Utility;

use Bellisq\Authority\Utility\AuthorityMethodMap;
use PHPUnit\Framework\TestCase;


class ZZAuthorityMethodMapTest
    extends TestCase
{
    public function testBehavior()
    {
        $cls = new class extends AuthorityMethodMap
        {
            public function __construct() { parent::__construct('Prefix'); }

            public function simple(): bool { return true; }

            public function authorize(int $t): bool { return $t === 3; }

            public function types($x, string $n = '334', ...$u): bool
            {
                return true;
            }
        };

        $this->assertTrue($cls->sufficient('prefix::simple'));
        $this->assertTrue($cls->sufficient('preFix::sImPle'));
        $this->assertTrue($cls->sufficient('prefix::simple '));
        $this->assertFalse($cls->sufficient('prefix::simple('));
        $this->assertFalse($cls->sufficient('prefix::simple ('));
        $this->assertTrue($cls->sufficient('prefix::simple()'));
        $this->assertTrue($cls->sufficient('prefix::simple ()'));
        $this->assertTrue($cls->sufficient('prefix::simple( )'));
        $this->assertFalse($cls->sufficient('prefix::simple(1)'));
        $this->assertFalse($cls->sufficient('prefix::simple (1)'));
        $this->assertFalse($cls->sufficient('prefix::simple( 1)'));
        $this->assertFalse($cls->sufficient('prefix::simple(1 )'));

        $this->assertFalse($cls->sufficient('prefix::authorize'));
        $this->assertFalse($cls->sufficient('prefix::authorize()'));
        $this->assertFalse($cls->sufficient('prefix::authorize(2)'));
        $this->assertTrue($cls->sufficient('prefix::authorize(3)'));
        $this->assertTrue($cls->sufficient('prefix::authorize (3)'));
        $this->assertTrue($cls->sufficient('prefix::authorize( 3)'));
        $this->assertTrue($cls->sufficient('prefix::authorize(3 )'));
        $this->assertFalse($cls->sufficient('prefix::authorize(2, 4)'));
        $this->assertFalse($cls->sufficient('prefix::authorize(3, 4)'));

        $this->assertFalse($cls->sufficient('prefix::types'));
        $this->assertFalse($cls->sufficient('prefix::types()'));
        $this->assertTrue($cls->sufficient('prefix::types(1)'));
        $this->assertTrue($cls->sufficient('prefix::types(1, 2)'));
        $this->assertTrue($cls->sufficient('prefix::types(1, 2, 3)'));
        $this->assertTrue($cls->sufficient('prefix::types(1, 2, 3, 4)'));
    }
}