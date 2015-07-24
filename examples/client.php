<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Datto\JsonRpc\Client;

$client = new Client();

$client->query(1, 'add', array(1, 2));

$request = $client->encode();

echo $request, "\n";
// {"jsonrpc":"2.0","id":1,"method":"add","params":[1,2]}
