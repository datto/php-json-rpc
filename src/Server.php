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

use Datto\JsonRpc\Exceptions\Exception;

/**
 * Class Server
 *
 * @link http://www.jsonrpc.org/specification JSON-RPC 2.0 Specifications
 *
 * @package Datto\JsonRpc
 */
class Server
{
    const VERSION = '2.0';

    /** @var Evaluator */
    private $evaluator;

    /**
     * @param Evaluator $evaluator
     */
    public function __construct(Evaluator $evaluator)
    {
        $this->evaluator = $evaluator;
    }

    /**
     * Processes the user input, and prepares a response (if necessary).
     *
     * @param string $json
     * Single request object, or an array of request objects, as a JSON string.
     *
     * @param array $additionalParams
     * A list of additional arguments to pass to the evaluator.
     *
     * @return string|null
     * Returns a response object (or an error object) as a JSON string, when a query is made.
     * Returns an array of response/error objects as a JSON string, when multiple queries are made.
     * Returns null, when no response is necessary.
     */
    public function reply($json, $additionalParams = array())
    {
        $output = $this->processInput($json, $additionalParams);

        if ($output === null) {
            return null;
        }

        return json_encode($output);
    }

    /**
     * Processes the user input, and prepares a response (if necessary).
     *
     * @param string $json
     * Single request object, or an array of request objects, as a JSON string.
     *
     * @param array $additionalParams
     * A list of additional arguments to pass to the evaluator.
     *
     * @return array|null
     * Returns a response object (or an error object) when a query is made.
     * Returns an array of response/error objects when multiple queries are made.
     * Returns null when no response is necessary.
     */
    private function processInput($json, $additionalParams = array())
    {
        if (!is_string($json)) {
            return self::parseError();
        }

        $input = json_decode($json, true);

        if (!is_array($input)) {
            return self::parseError();
        }

        if (count($input) === 0) {
            return self::requestError();
        }

        if (isset($input[0])) {
            return $this->processBatchRequests($input, $additionalParams);
        }

        return $this->processRequest($input, $additionalParams);
    }

    /**
     * Processes a batch of user requests, and prepares the response.
     *
     * @param array $input
     * Array of request objects.
     *
     * @param array $additionalParams
     * A list of additional arguments to pass to the evaluator.
     *
     * @return array|null
     * Returns a response/error object when a query is made.
     * Returns an array of response/error objects when multiple queries are made.
     * Returns null when no response is necessary.
     */
    private function processBatchRequests($input, $additionalParams = array())
    {
        $replies = array();

        foreach ($input as $request) {
            $reply = $this->processRequest($request);

            if ($reply !== null) {
                $replies[] = $reply;
            }
        }

        if (count($replies) === 0) {
            return null;
        }

        return $replies;
    }

    /**
     * Processes an individual request, and prepares the response.
     *
     * @param array $request
     * Single request object to be processed.
     *
     * @param array $additionalParams
     * A list of additional arguments to pass to the evaluator.
     *
     * @return array|null
     * Returns a response object or an error object.
     * Returns null when no response is necessary.
     */
    private function processRequest($request, $additionalParams = array())
    {
        if (!is_array($request)) {
            return self::requestError();
        }

        // The presence of the 'id' key indicates that a response is expected
        $isQuery = array_key_exists('id', $request);

        $id = &$request['id'];

        if (($id !== null) && !is_int($id) && !is_float($id) && !is_string($id)) {
            return self::requestError();
        }

        $version = &$request['jsonrpc'];

        if ($version !== self::VERSION) {
            return self::requestError($id);
        }

        $method = &$request['method'];

        if (!is_string($method)) {
            return self::requestError($id);
        }

        // The 'params' key is optional, but must be non-null when provided
        if (array_key_exists('params', $request)) {
            $arguments = $request['params'];

            if (!is_array($arguments)) {
                return self::requestError($id);
            }
        } else {
            $arguments = array();
        }

        if (count($additionalParams) > 0) {
            $arguments = array_merge($arguments, $additionalParams);
        }

        if ($isQuery) {
            return $this->processQuery($id, $method, $arguments);
        }

        $this->processNotification($method, $arguments);
        return null;
    }

    /**
     * Processes a query request and prepares the response.
     *
     * @param mixed $id
     * Client-supplied value that allows the client to associate the server response
     * with the original query.
     *
     * @param string $method
     * String value representing a method to invoke on the server.
     *
     * @param array $arguments
     * Array of arguments that will be passed to the method.
     *
     * @return array
     * Returns a response object or an error object.
     */
    private function processQuery($id, $method, $arguments)
    {
        try {
            $result = $this->evaluator->evaluate($method, $arguments);
            return self::response($id, $result);
        } catch (Exception $exception) {
            $code = $exception->getCode();
            $message = $exception->getMessage();
            $data = $exception->getData();

            return self::error($id, $code, $message, $data);
        }
    }

    /**
     * Processes a notification. No response is necessary.
     *
     * @param string $method
     * String value representing a method to invoke on the server.
     *
     * @param array $arguments
     * Array of arguments that will be passed to the method.
     */
    private function processNotification($method, $arguments)
    {
        try {
            $this->evaluator->evaluate($method, $arguments);
        } catch (Exception $exception) {
        }
    }

    /**
     * Returns an error object explaining that an error occurred while parsing
     * the JSON text input.
     *
     * @return array
     * Returns an error object.
     */
    private static function parseError()
    {
        return self::error(null, -32700, 'Parse error');
    }

    /**
     * Returns an error object explaining that the JSON input is not a valid
     * request object.
     *
     * @param mixed $id
     * Client-supplied value that allows the client to associate the server response
     * with the original query.
     *
     * @return array
     * Returns an error object.
     */
    private static function requestError($id = null)
    {
        return self::error($id, -32600, 'Invalid Request');
    }

    /**
     * Returns a properly-formatted error object.
     *
     * @param mixed $id
     * Client-supplied value that allows the client to associate the server response
     * with the original query.
     *
     * @param int $code
     * Integer value representing the general type of error encountered.
     *
     * @param string $message
     * Concise description of the error (ideally a single sentence).
     *
     * @param null|boolean|integer|float|string|array $data
     * An optional primitive value that contains additional information about
     * the error.
     *
     * @return array
     * Returns an error object.
     */
    private static function error($id, $code, $message, $data = null)
    {
        $error = array(
            'code' => $code,
            'message' => $message
        );

        if ($data !== null) {
            $error['data'] = $data;
        }

        return array(
            'jsonrpc' => self::VERSION,
            'id' => $id,
            'error' => $error
        );
    }

    /**
     * Returns a properly-formatted response object.
     *
     * @param mixed $id
     * Client-supplied value that allows the client to associate the server response
     * with the original query.
     *
     * @param mixed $result
     * Return value from the server method, which will now be delivered to the user.
     *
     * @return array
     * Returns a response object.
     */
    private static function response($id, $result)
    {
        return array(
            'jsonrpc' => self::VERSION,
            'id' => $id,
            'result' => $result
        );
    }
}
