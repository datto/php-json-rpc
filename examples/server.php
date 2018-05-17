<?php

use Datto\JsonRpc\Server;
use Datto\JsonRpc\Examples\Api;

require_once __DIR__ . '/../vendor/autoload.php';


$server = new Server(new Api());

$reply = $server->reply('{"jsonrpc":"2.0","id":1,"method":"add","params":[1,2]}');

echo $reply, "\n"; // {"jsonrpc":"2.0","id":1,"result":3}
