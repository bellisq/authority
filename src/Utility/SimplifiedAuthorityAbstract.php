<?php

namespace Bellisq\Authority\Utility;

use Bellisq\Authority\AuthorityInterface;


/**
 * [Abstract Class] Simplified Authority
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2017 Bellisq. All Rights Reserved.
 * @package bellisq/authority
 * @since 1.0.0
 */
abstract class SimplifiedAuthorityAbstract
    implements AuthorityInterface
{
    /**
     * Check whether or not this authority has sufficient permission.
     *
     * @param string $requirement
     * @return bool
     */
    abstract protected function sufficientSingle(string $requirement): bool;

    /**
     * Check whether or not this authority has sufficient permission for multiple requirements.
     *
     * @param string[] ...$requirements
     * @return bool
     */
    final public function sufficient(string ...$requirements): bool
    {
        foreach ($requirements as $requirement) if (!$this->sufficientSingle($requirement)) return false;
        return true;
    }
}