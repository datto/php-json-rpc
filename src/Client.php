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
 * Class Client
 *
 * @link http://www.jsonrpc.org/specification JSON-RPC 2.0 Specifications
 *
 * @package Datto\JsonRpc
 */
class Client
{
    /** @var string */
    const VERSION = '2.0';

    /** @var array */
    private $messages;

    public function __construct()
    {
        $this->messages = array();
    }

    /**
     * @param mixed $id
     * @param string $method
     * @param array $arguments
     */
    public function query($id, $method, $arguments)
    {
        $message = array(
            'jsonrpc' => self::VERSION,
            'id' => $id,
            'method' => $method
        );

        if ($arguments !== null) {
            $message['params'] = $arguments;
        }

        $this->messages[] = $message;
    }

    /**
     * @param string $method
     * @param array $arguments
     */
    public function notify($method, $arguments)
    {
        $message = array(
            'jsonrpc' => self::VERSION,
            'method' => $method
        );

        if ($arguments !== null) {
            $message['params'] = $arguments;
        }

        $this->messages[] = $message;
    }

    /**
     * Encodes the requests as a valid JSON-RPC 2.0 string
     *
     * @return null|string
     * Returns a valid JSON-RPC 2.0 message string
     * Returns null if there is nothing to encode
     */
    public function encode()
    {
        $count = count($this->messages);

        if ($count === 0) {
            return null;
        }

        if ($count === 1) {
            $output = array_shift($this->messages);
        } else {
            $output = $this->messages;
        }

        $this->messages = array();

        return json_encode($output);
    }

    /**
     * Translates a JSON-RPC 2.0 server response string into an associative array
     *
     * @param string $reply
     * Text reply from a JSON-RPC 2.0 server
     *
     * @return mixed
     * Returns an associative array containing the decoded server response
     * Returns null on error
     */
    public function decode($reply)
    {
        return @json_decode($reply, true);
    }
}
