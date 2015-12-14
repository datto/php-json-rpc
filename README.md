# JSON-RPC for PHP


## Overview

This package allows you to create and evaluate JSON-RPC messages, using your own
PHP code to evaluate the requests.

This package simply abstracts away the details of the JSON-RPC messaging format.
It does *not* provide a transportation layer:
* If you need to send messages over HTTP(S), then you should use the
[php-json-rpc-http](https://github.com/datto/php-json-rpc-http) package instead.
* If you need to send messages over an SSH tunnel, then you should use the
[php-json-rpc-ssh](https://github.com/datto/php-json-rpc-ssh) package.


## Features

* Correct: fully compliant with the [JSON-RPC 2.0 specifications](http://www.jsonrpc.org/specification) (100% unit-test coverage)
* Flexible: you can use your own code to evaluate the JSON-RPC method strings
* Minimalistic: extremely lightweight
* Ready to use, with working examples


## Examples

### Client

```php
$client = new Client();

$client->query(1, 'add', array(1, 2));

$message = $client->encode(); // {"jsonrpc":"2.0","id":1,"method":"add","params":[1,2]}
```

### Server

```php
$api = new Api();

$server = new Server($api);

$reply = $server->reply($message); // {"jsonrpc":"2.0","id":1,"result":3}
```

*See the "examples" folder for the full examples.*


## Requirements

* PHP >= 5.3


## License

This package is released under an open-source license: [LGPL-3.0](https://www.gnu.org/licenses/lgpl-3.0.html)


## Installation

If you're using [Composer](https://getcomposer.org/), you can use this package
([datto/json-rpc](https://packagist.org/packages/datto/json-rpc))
by inserting a line into the "require" section of your "composer.json" file:
```
        "datto/json-rpc": "~3.0"
```


## Getting started

1. Try the examples. You can run the examples from the project directory like this:
	```
	php examples/client.php
	php examples/server.php
	```

2. Take a look at the code "examples/src"--then replace it with your own!


## Unit tests

You can run the suite of unit tests from the project directory like this:
```
./vendor/bin/phpunit
```


## Author

[Spencer Mortensen](http://spencermortensen.com/contact/)
