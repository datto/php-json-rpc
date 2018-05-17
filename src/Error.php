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

namespace Datto\JsonRpc;

/**
 * Class Error
 * @package Datto\JsonRpc
 *
 * A description of an error that occurred on the server
 *
 * @link http://www.jsonrpc.org/specification#error_object
 */
class Error
{
    private $message;

    private $code;

    private $data;

    /**
     * @param $message
      * Short description of the error that occurred. This message SHOULD
      * be limited to a single, concise sentence.
     *
     * @param int $code
     * Integer identifying the type of error that occurred.
     *
     * @param null|boolean|integer|float|string|array $data
      * An optional primitive value that contains additional information about
     * the error.
     */
    public function __construct($message, $code, $data)
    {
        $this->message = $message;
        $this->code = $code;
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getData()
    {
        return $this->data;
    }
}
