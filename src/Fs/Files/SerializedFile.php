<?php

namespace Runn\Fs\Files;

use Runn\Serialization\SerializerInterface;
use Runn\Serialization\Serializers\Serialize;

/**
 * File with contents serialized into PHP serialized string format
 *
 * Class SerializedFile
 * @package Runn\Fs\Files
 */
class SerializedFile extends FileWithSerializer
{

    public function getSerializer(): ?SerializerInterface
    {
        return new Serialize();
    }

    public function setSerializer(?SerializerInterface $serializer)
    {
        throw new \BadMethodCallException();
    }

}
