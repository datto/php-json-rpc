<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Datto\JsonRpc\Server;

$interpreter = function ($method) {
    // Convert a JSON-RPC string method name into an actual callable method
    return array('\\Datto\\JsonRpc\\Examples\\Application\\Math', $method);
};

$server = new Server($interpreter);

$reply = $server->reply('{"jsonrpc":"2.0","id":1,"method":"add","params":[1,2]}');

echo $reply, "\n";
// {"jsonrpc":"2.0","id":1,"result":3}
