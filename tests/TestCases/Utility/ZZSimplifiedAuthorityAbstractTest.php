<?php

namespace Bellisq\Authority\Tests\TestCases\Utility;

use Bellisq\Authority\Utility\SimplifiedAuthorityAbstract;
use PHPUnit\Framework\TestCase;


class ZZSimplifiedAuthorityAbstractTest
    extends TestCase
{
    public function testBehavior()
    {
        $authority = new class extends SimplifiedAuthorityAbstract
        {
            /** @var string[] */
            private $tried = [];

            /**
             * Check whether or not this authority has sufficient permission.
             *
             * @param string $requirement
             * @return bool
             */
            protected function sufficientSingle(string $requirement): bool
            {
                $this->tried[] = $requirement;
                return true;
            }

            public function getValues(): array
            {
                return $this->tried;
            }
        };

        $this->assertTrue($authority->sufficient('Authority1', 'Authority2'));
        $this->assertEquals(['Authority1', 'Authority2'], $authority->getValues());
    }
}