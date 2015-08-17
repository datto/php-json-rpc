# JSON-RPC for PHP

## Features

* Fully compliant with the [JSON-RPC 2.0 specifications](http://www.jsonrpc.org/specification) (with 100% unit-test coverage)
* Flexible: you can use your own code to evaluate the JSON-RPC methods
* Ultra-lightweight

## Requirements

* PHP >= 5.3

## License

This package is released under an open-source license: [LGPL-3.0](https://www.gnu.org/licenses/lgpl-3.0.html)

## Examples

### Client

```php
$client = new Client();

$client->query(1, 'add', array(1, 2));

$message = $client->encode(); // {"jsonrpc":"2.0","id":1,"method":"add","params":[1,2]}
```

### Server

```php
$server = new Server(new Api());

$reply = $server->reply($message); // {"jsonrpc":"2.0","id":1,"result":3}
```

*See the "examples" folder for ready-to-use examples.*

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
