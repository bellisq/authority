<?php

namespace Bellisq\Authority;


/**
 * [Interface] Authority
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2017 Bellisq. All Rights Reserved.
 * @package bellisq/authority
 * @since 1.0.0
 */
interface AuthorityInterface
{
    /**
     * Check whether or not this authority has sufficient permission.
     *
     * @param string[] ...$requirements
     * @return bool
     */
    public function sufficient(string ...$requirements): bool;
}