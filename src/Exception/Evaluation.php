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

use Exception;
use Datto\JsonRpc;

/**
 * Class Evaluation
 * @package Datto\JsonRpc\Exception
 *
 * The JSON-RPC 2.0 specifications allow you to define your own error objects!
 * You can use this to communicate any issues that arise during the evaluation
 * of a request.
 *
 * @link http://www.jsonrpc.org/specification#error_object
 */
class Evaluation extends Exception implements JsonRpc\Exception
{
    /**
     * @param string $message
     * Short description of the error that occurred. This message SHOULD
     * be limited to a single, concise sentence.
     *
     * @param int $code
     * Integer identifying the type of error that occurred. As the author of
     * your API, you are free to define the error codes that you find useful
     * for your application.
     *
     * Please be aware that the error codes in the range from -32768 to -32000,
     * inclusive, have special meanings under the JSON-RPC 2.0 specification!
     * These error codes have already been taken, so they cannot also be used
     * as application-defined error codes. You can safely use any integer value
     * from outside the reserved range.
     */
    public function __construct($message = '', $code = 0)
    {
        if (!self::isValidCode($code)) {
            $code = 0;
        }

        if (!self::isValidMessage($message)) {
            $message = '';
        }

        parent::__construct($message, $code);
    }

    /**
     * @param int $code
     * Integer identifying the type of error that occurred. As the author of
     * your API, you are free to define the error codes that you find useful
     * for your application.
     *
     * Please be aware that the error codes in the range from -32768 to -32000,
     * inclusive, have special meanings under the JSON-RPC 2.0 specification!
     * These error codes have already been taken, so they cannot also be used
     * as application-defined error codes. You can safely use any integer value
     * from outside the reserved range.
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
     * @param string $message
     * Short description of the error that occurred. This message SHOULD
     * be limited to a single, concise sentence.
     *
     * @return bool
     * Returns true iff the value can be used as an application-defined
     * error message.
     */
    private static function isValidMessage($message)
    {
        return is_string($message);
    }
}
