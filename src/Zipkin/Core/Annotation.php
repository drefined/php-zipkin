<?php
namespace Drefined\Zipkin\Core;

class Annotation
{
    const CLIENT_SEND     = 'cs';
    const CLIENT_RECV     = 'cr';
    const SERVER_SEND     = 'ss';
    const SERVER_RECV     = 'sr';

    /** @var string $value */
    private $value;

    /** @var int $timestamp */
    private $timestamp;

    /** @var Endpoint|null $endpoint */
    private $endpoint;

    /**
     * @param int           $value
     * @param string        $timestamp
     * @param Endpoint|null $endpoint
     */
    public function __construct($value, $timestamp, Endpoint $endpoint = null)
    {
        $this->value     = $value;
        $this->timestamp = $timestamp;
        $this->endpoint  = $endpoint;
    }

    /**
     * @return int
     */
    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return Endpoint|null
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * @param Endpoint $endpoint
     */
    public function setEndpoint(Endpoint $endpoint)
    {
        $this->endpoint = $endpoint;
    }

    /**
     * @param string        $value
     * @param int|null      $timestamp
     * @param Endpoint|null $endpoint
     * @return Annotation
     */
    public static function generateTimestamp($value, $timestamp = null, Endpoint $endpoint = null)
    {
        if (empty($timestamp)) {
            $timestamp = Time::microseconds();
        }

        return new self($value, $timestamp, $endpoint);
    }

    /**
     * @param int|null $timestamp
     * @return Annotation
     */
    public static function generateClientSend($timestamp = null)
    {
        return self::generateTimestamp(self::CLIENT_SEND, $timestamp);
    }

    /**
     * @param int|null $timestamp
     * @return Annotation
     */
    public static function generateClientRecv($timestamp = null)
    {
        return self::generateTimestamp(self::CLIENT_RECV, $timestamp);
    }

    /**
     * @param int|null $timestamp
     * @return Annotation
     */
    public static function generateServerSend($timestamp = null)
    {
        return self::generateTimestamp(self::SERVER_SEND, $timestamp);
    }

    /**
     * @param int|null $timestamp
     * @return Annotation
     */
    public static function generateServerRecv($timestamp = null)
    {
        return self::generateTimestamp(self::SERVER_RECV, $timestamp);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'value'     => $this->getValue(),
            'timestamp' => $this->getTimestamp(),
            'endpoint'  => $this->getEndpoint()
                                ->toArray(),
        ];
    }
}
