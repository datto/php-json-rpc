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
 * Class Application
 * @package Datto\JsonRpc\Exception
 *
 * The JSON-RPC 2.0 specifications allows each application that evaluates a user
 * request to define its own custom error codes:
 *
 * @link http://www.jsonrpc.org/specification#error_object
 *
 * You can throw an "Application" exception to communicate any issues that arise
 * while your application is evaluating a user request.
 *
 * However:
 *
 * If one or more of the user-supplied arguments are invalid, then you should
 * report the issue through an "Argument" exception instead.
 */
class Application extends JsonRpc\Exception
{
    /**
     * @param string $message
     * Short description of the error that occurred. This message SHOULD
     * be limited to a single, concise sentence.
     *
     * @param int $code
     * Integer identifying the type of error that occurred. As the author of
     * a server-side application, you are free to define any error codes
     * that you find useful for your application.
     *
     * Please be aware that the error codes in the range from -32768 to -32000,
     * inclusive, have special meanings under the JSON-RPC 2.0 specification:
     * These error codes have already been taken, so they cannot be redefined
     * as application-defined error codes! However, you can safely use any
     * integer from outside this reserved range.
     *
     * @param null|boolean|integer|float|string|array $data
     * An optional primitive value that contains additional information about
     * the error. You're free to define the format of this data (e.g. you could
     * supply an array with detailed error information). Alternatively, you may
     * omit this field by providing a null value.
     */
    public function __construct($message, $code, $data = null)
    {
        if (!self::isValidMessage($message)) {
            $message = '';
        }

        if (!self::isValidCode($code)) {
            $code = 1;
        }

        if (!self::isValidData($data)) {
            $data = null;
        }

        parent::__construct($message, $code, $data);
    }

    /**
     * Determines whether a value can be used as an error message.
     *
     * @param string $input
     * Short description of the error that occurred. This message SHOULD
     * be limited to a single, concise sentence.
     *
     * @return bool
     * Returns true iff the value can be used as an error message.
     */
    private static function isValidMessage($input)
    {
        return is_string($input);
    }

    /**
     * Determines whether a value can be used as an application-defined error
     * code.
     *
     * @param int $code
     * Integer identifying the type of error that occurred. As the author of
     * a server-side application, you are free to define any error codes
     * that you find useful for your application.
     *
     * Please be aware that the error codes in the range from -32768 to -32000,
     * inclusive, have special meanings under the JSON-RPC 2.0 specification:
     * These error codes have already been taken, so they cannot be redefined
     * as application-defined error codes! However, you can safely use any
     * integer from outside this reserved range.
     *
     * @return bool
     * Returns true iff the value can be used as an application-defined
     * error code.
     */
    private static function isValidCode($code)
    {
        return is_int($code) && (($code < -32768) || (-32000 < $code));
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
