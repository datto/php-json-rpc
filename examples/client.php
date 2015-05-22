<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Datto\JsonRpc\Client;

$client = new Client();

$client->query(1, 'Math/subtract', array(5, 3));

$request = $client->encode();

echo $request, "\n"; // {"jsonrpc":"2.0","id":1,"method":"Math\/subtract","params":[5,3]}
