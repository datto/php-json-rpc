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
 * Class Response
 * @package Datto\JsonRpc
 *
 * A response from the server
 *
 * @link http://www.jsonrpc.org/specification#response_object
 */
class Response
{
    /** @var null|int|float|string */
    private $id;

    /** @var bool */
    private $isError;

    /** @var null|int|float|string|array */
    private $result;

    /** @var Error|null */
    private $error;

    public function __construct($id, $value, $isError)
    {
        if ($isError) {
            $result = null;
            $error = $value;
        } else {
            $result = $value;
            $error = null;
        }

        $this->id = $id;
        $this->result = $result;
        $this->error = $error;
        $this->isError = $isError;
    }

    /**
     * @return null|int|float|string
     * A unique identifier, used to match this server response with an earlier
     * user request
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * A response will contain either an error object, or a result, but not both.
     *
     * @return bool
     * True iff the response contains an error object
     * False iff the response contains a result
     */
    public function isError()
    {
        return $this->isError;
    }

    /**
     * @return null|int|float|string|array
     * The result returned by the server (if applicable)
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @return Error|null
     * An error object describing the server issue (if applicable)
     */
    public function getError()
    {
        return $this->error;
    }
}
