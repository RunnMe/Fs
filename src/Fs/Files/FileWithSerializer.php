<?php

namespace Runn\Fs\Files;

use Runn\Fs\File;
use Runn\Fs\FileAsStorageWithSerializerInterface;
use Runn\Serialization\SerializerInterface;
use Runn\Serialization\Serializers\PassThru;

/**
 * File with serializer (contains serialized contents)
 *
 * Class FileWithSerializer
 * @package Runn\Fs\Files
 */
class FileWithSerializer
    extends File
    implements FileAsStorageWithSerializerInterface
{

    /**
     * @var \Runn\Serialization\SerializerInterface|null
     */
    protected $serializer;

    public function setSerializer(?SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
        return $this;
    }

    public function getSerializer(): ?SerializerInterface
    {
        return $this->serializer ?: new PassThru();
    }


    protected function processContentsAfterLoad($contents)
    {
        return $this->getSerializer()->decode($contents);
    }

    protected function processContentsBeforeSave($contents)
    {
        return $this->getSerializer()->encode($contents);
    }

}
