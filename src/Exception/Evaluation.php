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

class Evaluation extends Exception implements JsonRpc\Exception
{
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

    private static function isValidCode($code)
    {
        // as defined by the JSON-RPC 2.0 spec, see http://www.jsonrpc.org/specification#error_object
        return is_int($code) && (-32768 <= $code && $code <= -32000);
    }

    private static function isValidMessage($message)
    {
        return is_string($message);
    }
}
