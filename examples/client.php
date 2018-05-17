<?php

use Datto\JsonRpc\Client;

require_once dirname(__DIR__) . '/vendor/autoload.php';



// Example 1. Single query
$client = new Client();

$client->query(1, 'add', array(1, 2));

$message = $client->encode();

echo "Example 1. Single query:\n{$message}\n\n";
// {"jsonrpc":"2.0","id":1,"method":"add","params":[1,2]}



// Example 2. Batch queries
$client = new Client();

$client->query(1, 'add', array(1, 2));
$client->query(2, 'add', array('a', 'b'));

$message = $client->encode();

echo "Example 2. Batch queries:\n{$message}\n\n";
// [{"jsonrpc":"2.0","id":1,"method":"add","params":[1,2]},{"jsonrpc":"2.0","id":2,"method":"add","params":["a","b"]}]



// Example 3. Valid server response
$client = new Client();

$reply = '[{"jsonrpc":"2.0","id":1,"result":3},{"jsonrpc":"2.0","id":2,"error":{"code":-32602,"message":"Invalid params"}}]';

$responses = $client->decode($reply);

echo "Example 3. Valid server response:\n";
foreach ($responses as $response) {
    $id = $response->getId();
    $isError = $response->isError();

    if ($isError) {
        $error = $response->getError();

        $errorProperties = array(
            'code' => $error->getCode(),
            'message' => $error->getMessage(),
            'data' => $error->getData()
        );

        echo " * id: {$id}, error: ", json_encode($errorProperties), "\n";
    } else {
        $result = $response->getResult();

        echo " * id: {$id}, result: ", json_encode($result), "\n";
    }
}
echo "\n";
// * id: 1, result: 3
// * id: 2, error: {"code":-32602,"message":"Invalid params","data":null}



// Example 4. Invalid server response
$client = new Client();

$reply = '{"jsonrpc":"2.0","id":';

try {
    $responses = $client->decode($reply);
} catch (ErrorException $exception) {
    echo "Example 3. Invalid server response:\n";

    $exceptionProperties = array(
        'code' => $exception->getCode(),
        'message' => $exception->getMessage()
    );

    echo "ErrorException: ", json_encode($exceptionProperties), "\n";
}
