<?php

namespace Runn\Fs\Files;

use Runn\Serialization\SerializerInterface;
use Runn\Serialization\Serializers\Json;

/**
 * File with contents serialized into JSON
 *
 * Class JsonFile
 * @package Runn\Fs\Files
 */
class JsonFile extends FileWithSerializer
{

    public function getSerializer(): ?SerializerInterface
    {
        return new Json();
    }

    public function setSerializer(?SerializerInterface $serializer)
    {
        throw new \BadMethodCallException();
    }

}
