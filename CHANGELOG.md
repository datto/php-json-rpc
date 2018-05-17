# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [5.0.0] - 2018-05-16
### Added
 - The "Client::decode" method now throws an "ErrorException" when the input is not a valid JSON-RPC 2.0 reply string
 - The "Client::query" and "Client::notify" methods now return the object handle, so you can chain method calls if you like

### Changed
 - The "Client::decode" method now returns a list of "Response" objects (rather than an associative array of raw JSON-RPC 2.0 keys and values)
 - The four exception classes have been moved (e.g. "Datto\JsonRpc\Exception\Application" => "Datto\JsonRpc\Exception**s**\\**Application**Exception")
