<?php

/**
 * Copyright (C) 2015 Datto, Inc.
 *
 * This file is part of PHP JSON-RPC.
 *
 * PHP JSON-RPC is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License, version 3,
 * as published by the Free Software Foundation.
 *
 * PHP JSON-RPC is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with PHP JSON-RPC. If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Spencer Mortensen <smortensen@datto.com>
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL-3.0
 * @copyright 2015 Datto, Inc.
 */

namespace Datto\JsonRpc\Tests\Example\Stateful;

use Datto\JsonRpc;

class Translator implements JsonRpc\Translator
{
    /** @var Math */
    private $math;

    public function __construct()
    {
        $this->math = new Math();
    }

    /**
     * @param string $name
     *
     * @return callable|null
     */
    public function getCallable($name)
    {
        if (!self::isValidName($name)) {
            return null;
        }

        return array($this->math, $name);
    }

    /**
     * @param mixed $input
     *
     * @return bool
     * Returns true if and only if the input is a valid method name
     */
    private static function isValidName($input)
    {
        if (!is_string($input)) {
            return false;
        }

        $validPattern = '~^[a-zA-Z0-9]+$~';

        return preg_match($validPattern, $input) === 1;
    }
}
