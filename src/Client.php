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

use ErrorException;

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
     *
     * @return self
     * Returns the object handle, so you can chain method calls if you like
     */
    public function query($id, $method, array $arguments)
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
        return $this;
    }

    /**
     * @param string $method
     * @param array $arguments
     *
     * @return self
     * Returns the object handle, so you can chain method calls if you like
     */
    public function notify($method, array $arguments)
    {
        $message = array(
            'jsonrpc' => self::VERSION,
            'method' => $method
        );

        if ($arguments !== null) {
            $message['params'] = $arguments;
        }

        $this->messages[] = $message;
        return $this;
    }

    /**
     * Encodes the requests as a valid JSON-RPC 2.0 string
     *
     * This also resets the Client, so you can perform more queries using
     * the same Client object.
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
     * Translates a JSON-RPC 2.0 server reply into an array of "Response"
     * objects
     *
     * @param string $input
     * String reply from a JSON-RPC 2.0 server
     *
     * @return Response[]
     * Returns a zero-indexed array of "Response" objects
     *
     * @throws ErrorException
     * Throws an "ErrorException" if the reply was not well-formed
     */
    public function decode($input)
    {
        set_error_handler(__CLASS__ . '::onError');
        $value = json_decode($input, true);
        restore_error_handler();

        if (($value === null) && (strtolower(trim($input)) !== 'null')) {
            $valuePhp = self::getValuePhp($input);
            throw new ErrorException("Invalid JSON: {$valuePhp}");
        }

        if (!$this->getReply($value, $output)) {
            $valuePhp = self::getValuePhp($input);
            throw new ErrorException("Invalid JSON-RPC 2.0 response: {$valuePhp}");
        }

        return $output;
    }

    private static function getValuePhp($value)
    {
        if (is_null($value)) {
            return 'null';
        }

        if (is_resource($value)) {
            $id = (int)$value;
            return "resource({$id})";
        }

        return var_export($value, true);
    }

    private function getReply($input, &$output)
    {
        if ($this->getResponse($input, $response)) {
            $output = array($response);
            return true;
        }

        return $this->getBatchResponses($input, $output);
    }

    private function getResponse($input, &$output)
    {
        if (
            is_array($input) &&
            $this->getVersion($input) &&
            $this->getId($input, $id) &&
            $this->getValue($input, $value, $isError)
        ) {
            $output = new Response($id, $value, $isError);
            return true;
        }

        return false;
    }

    private function getVersion(array $input)
    {
        return isset($input['jsonrpc']) && ($input['jsonrpc'] === '2.0');
    }

    private function getId(array $input, &$id)
    {
        if (!array_key_exists('id', $input)) {
            return false;
        }

        $id = $input['id'];

        return is_null($id) || is_int($id) || is_float($id) || is_string($id);
    }

    private function getValue($input, &$value, &$isError)
    {
        return $this->getResult($input, $value, $isError) ||
            $this->getError($input, $value, $isError);
    }

    private function getResult(array $input, &$value, &$isError)
    {
        if (!array_key_exists('result', $input)) {
            return false;
        }

        $value = $input['result'];
        $isError = false;

        return true;
    }

    private function getError(array $input, &$value, &$isError)
    {
        if (!isset($input['error'])) {
            return false;
        }

        $error = $input['error'];

        if (
            is_array($error) &&
            $this->getMessage($error, $message) &&
            $this->getCode($error, $code) &&
            $this->getData($error, $data)
        ) {
            $value = new Error($message, $code, $data);
            $isError = true;
            return true;
        }

        return false;
    }

    private function getCode(array $input, &$code)
    {
        if (!isset($input['code'])) {
            return false;
        }

        $code = $input['code'];

        return is_int($code);
    }

    private function getMessage(array $input, &$message)
    {
        if (!isset($input['message'])) {
            return false;
        }

        $message = $input['message'];

        return is_string($message);
    }

    private function getData(array $input, &$data)
    {
        if (array_key_exists('data', $input)) {
            $data = $input['data'];
        } else {
            $data = null;
        }

        return true;
    }

    private function getBatchResponses($input, &$output)
    {
        if (!is_array($input)) {
            return false;
        }

        $results = array();
        $i = 0;

        foreach ($input as $key => $value) {
            if ($key !== $i++) {
                return false;
            }

            if (!$this->getResponse($value, $results[])) {
                return false;
            }
        }

        $output = $results;
        return true;
    }

    public static function onError($level, $message, $file, $line)
    {
        $message = trim($message);
        $code = 0;

        throw new ErrorException($message, $code, $level, $file, $line);
    }
}
