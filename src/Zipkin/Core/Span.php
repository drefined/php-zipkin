<?php
namespace Drefined\Zipkin\Core;

class Span
{
    /** @var string $name */
    private $name;

    /** @var Identifier $traceId */
    private $traceId;

    /** @var Identifier $spanId */
    private $spanId;

    /** @var Identifier|null $parentSpanId (optional) */
    private $parentSpanId;

    /** @var Annotation[] $annotations */
    private $annotations;

    /** @var BinaryAnnotation[] $binaryAnnotations */
    private $binaryAnnotations;

    /** @var bool $debug (optional) */
    private $debug;

    /** @var int|null $timestamp (optional) */
    private $timestamp;

    /** @var int|null $duration (optional) */
    private $duration;

    /**
     * @param string             $name
     * @param Identifier         $traceId
     * @param Identifier         $spanId
     * @param Identifier|null    $parentSpanId
     * @param Annotation[]       $annotations
     * @param BinaryAnnotation[] $binaryAnnotations
     * @param bool|null          $debug
     * @param int|null           $timestamp
     * @param int|null           $duration
     */
    public function __construct(
        $name,
        Identifier $traceId,
        Identifier $spanId,
        Identifier $parentSpanId = null,
        array $annotations = [],
        array $binaryAnnotations = [],
        $debug = false,
        $timestamp = null,
        $duration = null
    ) {
        $this->name              = $name;
        $this->traceId           = $traceId;
        $this->spanId            = $spanId;
        $this->parentSpanId      = $parentSpanId;
        $this->annotations       = $annotations;
        $this->binaryAnnotations = $binaryAnnotations;
        $this->debug             = $debug;
        $this->timestamp         = $timestamp;
        $this->duration          = $duration;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Identifier
     */
    public function getTraceId(): Identifier
    {
        return $this->traceId;
    }

    /**
     * @return Identifier
     */
    public function getSpanId(): Identifier
    {
        return $this->spanId;
    }

    /**
     * @return Identifier|null
     */
    public function getParentSpanId()
    {
        return $this->parentSpanId;
    }

    /**
     * @return Annotation[]
     */
    public function getAnnotations(): array
    {
        return $this->annotations;
    }

    /**
     * @return BinaryAnnotation[]
     */
    public function getBinaryAnnotations(): array
    {
        return $this->binaryAnnotations;
    }

    /**
     * @return bool
     */
    public function getDebug(): bool
    {
        return $this->debug;
    }

    /**
     * @return int|null
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @return int|null
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param Annotation[] $annotations
     */
    public function setAnnotations(array $annotations)
    {
        $this->annotations = $annotations;
    }

    /**
     * @param BinaryAnnotation[] $binaryAnnotations
     */
    public function setBinaryAnnotations(array $binaryAnnotations)
    {
        $this->binaryAnnotations = $binaryAnnotations;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $parentSpanId = (string)$this->getParentSpanId();

        return [
            'id'                => (string)$this->getSpanId(),
            'name'              => $this->getName(),
            'traceId'           => (string)$this->getTraceId(),
            'parentId'          => (empty($parentSpanId)) ? null : $parentSpanId,
            'timestamp'         => $this->getTimestamp(),
            'duration'          => $this->getDuration(),
            'debug'             => $this->getDebug(),
            'annotations'       => array_map([$this, 'annotationToArray'], $this->getAnnotations()),
            'binaryAnnotations' => array_map([$this, 'binaryAnnotationToArray'], $this->getBinaryAnnotations()),
        ];
    }

    /**
     * @param Annotation $annotation
     * @return array
     */
    public function annotationToArray(Annotation $annotation): array
    {
        return $annotation->toArray();
    }

    /**
     * @param BinaryAnnotation $binaryAnnotation
     * @return array
     */
    public function binaryAnnotationToArray(BinaryAnnotation $binaryAnnotation): array
    {
        return $binaryAnnotation->toArray();
    }
}
