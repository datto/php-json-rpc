# JSON-RPC for PHP

## Features

* Fully compliant with the [JSON-RPC 2.0 specifications](http://www.jsonrpc.org/specification) (with 100% unit-test coverage)
* Flexible: you can choose your own system for interpreting the JSON-RPC method strings
* Minimalistic: just two tiny files

## Requirements

* PHP >= 5.3

## License

This package is released under an open-source license: [LGPL-3.0](https://www.gnu.org/licenses/lgpl-3.0.html)

## Examples

### Client

```php
$client = new Client();

$client->query(1, 'Math/subtract', array(5, 3));

$request = $client->encode(); // {"jsonrpc":"2.0","id":1,"method":"Math\/subtract","params":[5,3]}
```

### Server

```php
$translator = new Translator();
$server = new Server($translator);

$request = '{"jsonrpc":"2.0","id":1,"method":"Math\/subtract","params":[5,3]}';

$reply = $server->reply($request); // {"jsonrpc":"2.0","id":1,"result":2}
```

*See the "examples" folder for ready-to-use examples.*

## Installation

If you're using [Composer](https://getcomposer.org/), you can use this package
by inserting a line in the "require" section of your "composer.json" file:
```
        "datto/json-rpc": "1.0.*"
```

## Getting started

1. Try the examples. You can run the examples from the project directory like this:
	```
	php examples/client.php
	php examples/server.php
	```

2. Take a look at the examples in the "tests" directory, and then replace them with
your own code.

## Unit tests

You can run the suite of unit tests from the project directory like this:
```
./vendor/bin/phpunit
```

## Author

[Spencer Mortensen](http://spencermortensen.com/contact/)
