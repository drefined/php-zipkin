# PHP-Zipkin

This is an unofficial PHP library for OpenZipkin.

## Status

Incomplete, only has one transport with zero integrations. This library contains a very minimal implementation for just sending spans to zipkin.

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
