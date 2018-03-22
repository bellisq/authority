<?php

namespace Bellisq\Authority\Utility;

use Closure;
use ReflectionException;
use ReflectionFunction;


/**
 * [Abstract Class] Authority Method Mapping
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2017 Bellisq. All Rights Reserved.
 * @package bellisq/authority
 * @since 1.0.0
 */
abstract class AuthorityMethodMap
    extends SimplifiedAuthorityAbstract
{
    /** @var int */
    private $len;

    /** @var string */
    private $prefix;

    /**
     * MethodMapAuthority constructor.
     *
     * @param string $prefix
     */
    protected function __construct(string $prefix = '')
    {
        $this->prefix = $prefix;
        $this->len = strlen($prefix);
    }

    /**
     * Check whether or not this authority has sufficient permission.
     *
     * prefix = 'Prefix'
     * requirement = 'Prefix::Permission::Name(3, 4)'
     *
     * Then call $this->permissionName(3, 4) and pass-through the return value.
     *
     * @param string $requirement
     * @return bool
     */
    final protected function sufficientSingle(string $requirement): bool
    {
        $requirement = trim($requirement);

        // prefix
        if (strtolower(substr($requirement, 0, $this->len)) !== strtolower($this->prefix))
            return false;

        $requirement = substr($requirement, $this->len);

        $e = explode('(', trim($requirement), 2);

        // method
        $name = strtolower(str_replace('::', '', trim($e[0])));
        if (1 !== preg_match('@^[a-zA-Z][a-zA-Z0-9]*$@u', $name)) return false;

        $callback = [$this, $name];
        if (!is_callable($callback)) return false;
        $closure = Closure::fromCallable($callback);
        try {
            $refFunc = new ReflectionFunction($closure);
        } catch (ReflectionException $exception) {
            return false;
        }

        // params
        $params = $refFunc->getParameters();
        $argMin = 0;
        $argMax = 0;
        $types = [-1 => null];
        foreach ($params as $i => $param) {
            $types[$i] = null;
            if ($param->hasType()) {
                if (!$param->getType()->isBuiltin()) return false;
                $types[-1] = $types[$i] = $param->getType()->getName();
            }
            if ($param->isVariadic()) {
                $argMax = -1;
                break;
            }
            if ($param->isOptional()) {
                $argMax++;
            } else {
                $argMin = ++$argMax;
            }
        }

        // arguments
        $arguments = [];
        if (isset($e[1])) {
            $joinedArguments = $e[1];
            if (substr($joinedArguments, -1) !== ')') return false;

            $joinedArguments = substr($joinedArguments, 0, -1);
            foreach (explode(',', $joinedArguments) as $arg) $arguments[] = trim($arg);
        }
        if (count($arguments) === 1 && $arguments[0] === '') $arguments = [];

        if (count($arguments) < $argMin) return false;
        if ($argMax !== -1 && $argMax < count($arguments)) return false;

        // call
        return $callback(...$arguments);
    }
}