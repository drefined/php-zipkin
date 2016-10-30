<?php
namespace Drefined\Zipkin\Instrumentation\Laravel\Middleware;

use Closure;
use Drefined\Zipkin\Core\Annotation;
use Drefined\Zipkin\Core\BinaryAnnotation;
use Drefined\Zipkin\Core\Endpoint;
use Drefined\Zipkin\Instrumentation\Laravel\Services\ZipkinTracingService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EnableZipkinTracing
{
    /**
     * The application instance.
     *
     * @var Application $app
     */
    protected $app;

    /**
     * Create a new middleware instance.
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $method    = $request->getMethod();
        $uri       = $request->getRequestUri();
        $query     = $request->query->all();
        $ipAddress = $request->server('SERVER_ADDR') ?? '127.0.0.1';
        $port      = $request->server('SERVER_PORT');
        $name      = "{$method} {$uri}";

        $spanId       = $request->header('X-B3-SpanId') ?? null;
        $parentSpanId = $request->header('X-B3-ParentSpanId') ?? null;
        $sampled      = $request->header('X-B3-Sampled') ?? 1.0;
        $debug        = $request->header('X-B3-Flags') ?? false;

        /** @var ZipkinTracingService $tracingService */
        $tracingService = $this->app->make(ZipkinTracingService::class);

        $endpoint = new Endpoint($ipAddress, $port, 'laravel-app');
        $tracingService->createTrace(null, $endpoint, $sampled, $debug);

        $trace = $tracingService->getTrace();
        $trace->createNewSpan($name, null, $spanId, $parentSpanId);
        $trace->record(
            [Annotation::generateServerRecv()],
            [
                BinaryAnnotation::generateString('server.env', $this->app->environment()),
                BinaryAnnotation::generateString('server.request.uri', $uri),
                BinaryAnnotation::generateString('server.request.query', json_encode($query)),
            ]
        );

        return $next($request);
    }

    public function terminate(Request $request, Response $response)
    {
        /** @var ZipkinTracingService $tracingService */
        $tracingService = $this->app->make(ZipkinTracingService::class);

        $trace = $tracingService->getTrace();
        $trace->record(
            [Annotation::generateServerSend()],
            [BinaryAnnotation::generateString('server.response.http_status_code', $response->getStatusCode())]
        );
    }
}
