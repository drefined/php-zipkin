<?php
namespace Drefined\Zipkin\Transport;

use Drefined\Zipkin\Core\Span;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;

class HTTPLogger implements LoggerInterface
{
    /** @var ClientInterface $client */
    private $client;

    /** @var string $baseUrl */
    private $baseUrl;

    /**
     * @param ClientInterface $client
     * @param string          $baseUrl
     */
    public function __construct(ClientInterface $client, $baseUrl = 'http://localhost:9411/api/v1/spans')
    {
        $this->client  = $client;
        $this->baseUrl = $baseUrl;
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return $this->baseUrl;
    }

    /**
     * @param Span[] $spans
     * @throws \Exception
     */
    public function trace(array $spans)
    {
        try {
            $options = [
                'json' => array_map([$this, 'spanToArray'], $spans),
            ];

            $this->client->request('POST', $this->baseUrl, $options);
        } catch (RequestException $e) {
            throw new \Exception("Failed to publish trace: {$e->getMessage()}", $e->getCode(), $e);
        }
    }

    /**
     * @param Span $span
     * @return array
     */
    public function spanToArray(Span $span): array
    {
        return $span->toArray();
    }
}
