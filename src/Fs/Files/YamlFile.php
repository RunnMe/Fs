<?php

namespace Runn\Fs\Files;

use Runn\Serialization\SerializerInterface;
use Runn\Serialization\Serializers\Yaml;

/**
 * File with contents serialized into YAML
 *
 * Class YamlFile
 * @package Runn\Fs\Files
 */
class YamlFile extends FileWithSerializer
{

    public function setSerializer(?SerializerInterface $serializer)
    {
        throw new \BadMethodCallException();
    }


    public function getSerializer(): ?SerializerInterface
    {
        return new Yaml();
    }

}
