<?php

namespace Bellisq\Authority\Implementations;

use Bellisq\Authority\Utility\SimplifiedAuthorityAbstract;


/**
 * [Class] Permission Container
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2017 Bellisq. All Rights Reserved.
 * @package bellisq/authority
 * @since 1.0.0
 */
class PermissionContainer
    extends SimplifiedAuthorityAbstract
{
    /** @var string[] */
    private $flippedAuthorities;

    /**
     * PermissionContainer constructor.
     * @param string[] ...$permission
     */
    public function __construct(string ...$permission)
    {
        $this->flippedAuthorities = array_flip(array_unique($permission));
    }

    /**
     * Check whether or not this authority has sufficient permission.
     *
     * @param string $requirement
     * @return bool
     */
    protected function sufficientSingle(string $requirement): bool
    {
        return isset($this->flippedAuthorities[$requirement]);
    }
}