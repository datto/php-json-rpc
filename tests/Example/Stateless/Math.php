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

namespace Datto\JsonRpc\Tests\Example\Stateless;

class Math
{
    /**
     * Returns the value $a - $b
     *
     * @param mixed $minuend
     * @param mixed $subtrahend
     *
     * @return int|null
     * Returns $a - $b if both $a and $b are integers
     * Returns null otherwise
     */
    public static function subtract($minuend, $subtrahend)
    {
        if (!is_int($minuend) || !is_int($subtrahend)) {
            return null;
        }

        return $minuend - $subtrahend;
    }
}
