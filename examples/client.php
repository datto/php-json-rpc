<?php

use Datto\JsonRpc\Client;

require_once __DIR__ . '/../vendor/autoload.php';


$client = new Client();

$client->query(1, 'add', array(1, 2));

$message = $client->encode();


echo $message, "\n"; // {"jsonrpc":"2.0","id":1,"method":"add","params":[1,2]}
