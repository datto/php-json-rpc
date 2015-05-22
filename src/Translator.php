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
 * Interface Translator
 *
 * A "Translator" object translates a string method name to an actual
 * callable method. This allows the JSON-RPC server to interpret the
 * "method" argument in a request.
 *
 * You should create your a "MethodTranslator" class that will invoke your own codebase.
 * If you'd like an example, the "tests" directory has both stateful and stateless
 * (REST-style) examples.
 *
 * @package Datto\JsonRpc
 */
interface Translator
{
    /**
     * @param string $methodName
     * String value representing the method to invoke on the server.
     *
     * @return callable | null
     * Returns the corresponding callable method.
     * Returns null on error.
     */
    public function getCallable($methodName);
}
