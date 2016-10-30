<?php

use Drefined\Zipkin\Core\Annotation;
use Drefined\Zipkin\Core\BinaryAnnotation;
use Drefined\Zipkin\Core\Endpoint;
use Drefined\Zipkin\Core\Trace;

class TraceTest extends \PHPUnit\Framework\TestCase
{
    // Ideally we should refactor this file for unit tests instead of an integration test.
    public function testTrace()
    {
        $trace = new Trace(null, null, 1.0, true);
        $trace->setEndpoint(new Endpoint('127.0.0.1', 8080, 'test-trace'));

        $trace->createNewSpan('test-server-trace');
        $trace->record([Annotation::generateServerRecv()], [BinaryAnnotation::generateString('server.request.uri', '/server')]);

        // parent: test-server-trace
        $trace->createNewSpan('test-client-trace');
        $trace->record([Annotation::generateClientSend()], [BinaryAnnotation::generateString('client.request.uri', '/client')]);
        sleep(1);

        // parent: test-client-trace
        $trace->createNewSpan('test-server-trace-2');
        $trace->record([Annotation::generateServerRecv()], [BinaryAnnotation::generateString('server.request.uri', '/server2')]);
        sleep(2);
        $trace->record([Annotation::generateClientRecv()], [BinaryAnnotation::generateString('server.response', 200)]);
        $trace->popSpan();

        $trace->record([Annotation::generateClientRecv()], [BinaryAnnotation::generateString('client.response', 200)]);
        $trace->popSpan();

        // parent: test-server-trace
        $trace->createNewSpan('test-client-trace-2');
        $trace->record([Annotation::generateClientSend()], [BinaryAnnotation::generateString('client.request.uri', '/client2')]);
        sleep(1);
        $trace->record([Annotation::generateClientRecv()], [BinaryAnnotation::generateString('client.response', 200)]);
        $trace->popSpan();

        $trace->record([Annotation::generateServerSend()], [BinaryAnnotation::generateString('server.response', 200)]);

        $traceId = (string)$trace->getTraceId();

        echo "http://localhost:9411/api/v1/trace/{$traceId}";

        // TODO: Assert trace is the same from api call http://localhost:9411/api/v1/trace/{traceId}
    }
}
