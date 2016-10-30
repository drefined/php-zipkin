<?php
namespace Drefined\Zipkin\Core;

class BinaryAnnotation
{
    const TYPE_BOOL   = 0;
    const TYPE_BYTES  = 1;
    const TYPE_I16    = 2;
    const TYPE_I32    = 3;
    const TYPE_I64    = 4;
    const TYPE_DOUBLE = 5;
    const TYPE_STRING = 6;

    /** @var string $key */
    private $key;

    /** @var string $value */
    private $value;

    /** @var int $type */
    private $type;

    /** @var Endpoint|null $endpoint */
    private $endpoint;

    /**
     * @param string        $key
     * @param string        $value
     * @param int           $type
     * @param Endpoint|null $endpoint
     */
    public function __construct($key, $value, $type, Endpoint $endpoint = null)
    {
        $this->key      = $key;
        $this->value    = $value;
        $this->type     = $type;
        $this->endpoint = $endpoint;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
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
    public function setEndpoint($endpoint)
    {
        $this->endpoint = $endpoint;
    }

    /**
     * @param string $key
     * @param string $value
     * @return BinaryAnnotation
     */
    public static function generateString($key, $value)
    {
        return new self($key, $value, self::TYPE_STRING);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'key'      => $this->getKey(),
            'value'    => $this->getValue(),
            'endpoint' => $this->getEndpoint()
                               ->toArray(),
        ];
    }
}
