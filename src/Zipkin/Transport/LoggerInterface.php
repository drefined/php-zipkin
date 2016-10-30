<?php
namespace Drefined\Zipkin\Transport;

use Drefined\Zipkin\Core\Span;

interface LoggerInterface
{
    /**
     * @param Span[] $spans
     * @throws \Exception
     */
    public function trace(array $spans);
}
