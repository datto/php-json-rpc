<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Datto\JsonRpc\Tests\Example\Stateless\Translator;
use Datto\JsonRpc\Server;

$translator = new Translator();
$server = new Server($translator);

$request = '{"jsonrpc":"2.0","id":1,"method":"Math\/subtract","params":[5,3]}';

$reply = $server->reply($request);

echo $reply, "\n"; // {"jsonrpc":"2.0","id":1,"result":2}
