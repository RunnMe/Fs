<?php

namespace Runn\Fs;

use Runn\Serialization\SerializerInterface;
use Runn\Serialization\Serializers\Serialize;

class SerializedFile
    extends FileWithSerializer
{

    public function getSerializer(): SerializerInterface
    {
        return new Serialize();
    }

    public function setSerializer(SerializerInterface $serializer)
    {
        throw new \BadMethodCallException();
    }

}