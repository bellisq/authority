<?php

namespace Bellisq\Authority\Implementations;

use Bellisq\Authority\AuthorityInterface;
use Bellisq\Authority\Utility\SimplifiedAuthorityAbstract;


/**
 * [Class] Aggregate Authority
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2017 Bellisq. All Rights Reserved.
 * @package bellisq/authority
 * @since 1.0.0
 */
class AggregateAuthority
    extends SimplifiedAuthorityAbstract
{
    /** @var AuthorityInterface[] */
    private $authorities;

    /**
     * AggregateAuthority constructor.
     *
     * @param AuthorityInterface[]|null[] ...$authorities
     */
    public function __construct(?AuthorityInterface ...$authorities)
    {
        $authArray = [];
        foreach ($authorities as $authority) {
            if (is_null($authority)) continue;
            if ($authority instanceof AggregateAuthority) {
                $authArray = array_merge($authArray, $authority->authorities);
            } else {
                $authArray[] = $authority;
            }
        }
        $this->authorities = $authArray;
    }

    /**
     * Check whether or not this authority has sufficient permission.
     *
     * @param string $requirement
     * @return bool
     */
    final protected function sufficientSingle(string $requirement): bool
    {
        foreach ($this->authorities as $authority) if ($authority->sufficient($requirement)) return true;
        return false;
    }
}