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

namespace Datto\JsonRpc\Exception;

use Datto\JsonRpc;

/**
 * Class Implementation
 * @package Datto\JsonRpc\Exception
 *
 * The JSON-RPC 2.0 specifications allows each codebase that implements
 * the specifications to define its own custom error codes:
 *
 * @link http://www.jsonrpc.org/specification#error_object
 *
 * You can throw an "Implementation" exception to communicate any issues that
 * arise while your JSON-RPC 2.0 implementation is processing a request.
 *
 * However:
 *
 * If a method cannot be called (e.g. if the method doesn't exist, or is a
 * private method), then you should report the issue through the "Method"
 * exception instead.
 *
 * If the method exists, but the user-supplied arguments are incompatible with
 * the method's type signature, or one or more of the arguments is invalid,
 * then you should report the issue through an "Argument" exception.
 *
 * Finally, if the issue did not arise within your codebase, but instead arose
 * within the application-specific library code that actually evaluated the
 * request, then you should report the issue through an "Application" exception.
 */
class Implementation extends JsonRpc\Exception
{
    /**
     * @param int $code
     * Integer identifying the type of error that occurred. As the author of a
     * JSON-RPC 2.0 implementation, you are free to define any custom error code
     * that you find useful for your implementation, as long as your error code
     * falls within the range from -32099 to -32000 inclusive.
     *
     * @param null|boolean|integer|float|string|array $data
     * An optional primitive value that contains additional information about
     * the error. You're free to define the format of this data (e.g. you could
     * supply an array with detailed error information). Alternatively, you may
     * omit this field by providing a null value.
     */
    public function __construct($code, $data = null)
    {
        if (!self::isValidCode($code)) {
            $code = -32099;
        }

        if (!self::isValidData($data)) {
            $data = null;
        }

        parent::__construct('Server error', $code, $data);
    }

    /**
     * @param int $code
     *
     * @return bool
     * Returns true iff the value can be used as an implementation-defined
     * error code.
     */
    private static function isValidCode($code)
    {
        return is_int($code) && (-32099 <= $code) && ($code <= -32000);
    }

    /**
     * Determines whether a value can be used as the data value in an error
     * object.
     *
     * @param null|boolean|integer|float|string|array $input
     * An optional primitive value that contains additional information about
     * the error. You're free to define the format of this data (e.g. you could
     * supply an array with detailed error information). Alternatively, you may
     * omit this field by supplying a null value.
     *
     * @return bool
     * Returns true iff the value can be used as the data value in an error
     * object.
     */
    private static function isValidData($input)
    {
        $type = gettype($input);

        return ($type === 'array')
            || ($type === 'string')
            || ($type === 'double')
            || ($type === 'integer')
            || ($type === 'boolean')
            || ($type === 'NULL');
    }
}
