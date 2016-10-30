# PHP-Zipkin

This is an unofficial PHP library for OpenZipkin.

## Status

Incomplete, only has one transport with zero integrations. This library contains a very minimal implementation for just sending spans to zipkin.

## Getting started

The recommended way to install PHP-Zipkin is through [Composer](https://getcomposer.org/)

```bash
composer require drefined/php-zipkin
```

## Example usage

```php
<?php
$client   = new \GuzzleHttp\Client();
$logger   = new \Drefined\Zipkin\Transport\HTTPLogger($client);
$tracer   = new \Drefined\Zipkin\Tracer($logger, 1.0, true);
$endpoint = new \Drefined\Zipkin\Core\Endpoint('127.0.0.1', 8080, 'test-trace');
$trace    = new \Drefined\Zipkin\Core\Trace($tracer, $endpoint);

$trace->createNewSpan('test-server-trace');

$trace->record(
    [Annotation::generateServerRecv()],
    [BinaryAnnotation::generateString('server.request.uri', '/server')]
);

$trace->record(
    [Annotation::generateServerSend()],
    [BinaryAnnotation::generateString('server.response', 200)]
);

```

## Todo

* Add Laravel integration
* Add Symfony integration
* Add Redis wrapper
* Add HTTP wrapper
* Add PDO wrapper
* Add Scribe transport
* Add Kafka transport

## Inspired By

* [Tolerance/Tolerance](https://github.com/Tolerance/Tolerance)
* [Jimdo/hoopak](https://github.com/Jimdo/hoopak)

## Reference

* [Instrumenting a library](http://zipkin.io/pages/instrumenting.html)
* [Core data structures](thrift/zipkinCore.thrift)
* [openzipkin/zipkin-api](https://github.com/openzipkin/zipkin-api)

### Generating PHP thrift classes

* ls thrift/ | xargs -I {} thrift --gen php thrift/{}

## License

[![license](https://img.shields.io/github/license/mashape/apistatus.svg)](LICENSE)

## Contributors

* David Phruksukarn
