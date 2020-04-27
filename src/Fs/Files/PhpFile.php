<?php

namespace Runn\Fs\Files;

use Runn\Fs\Exceptions\FileNotReadable;
use Runn\Serialization\SerializerInterface;
use Runn\Serialization\Serializers\Php;

/**
 * File with contents serialized into PHP array
 *
 * Class PhpFile
 * @package Runn\Fs\Files
 */
class PhpFile extends FileWithSerializer
{

    public function getSerializer(): ?SerializerInterface
    {
        return new Php();
    }

    public function setSerializer(?SerializerInterface $serializer)
    {
        throw new \BadMethodCallException();
    }

    protected function processContentsBeforeSave($contents)
    {
        return '<?php' . PHP_EOL . PHP_EOL . 'return ' . parent::processContentsBeforeSave($contents). ';';
    }

    /**
     * @return $this
     * @throws \Runn\Serialization\DecodeException
     * @throws \Runn\Fs\Exceptions\FileNotReadable
     */
    public function load()
    {
        if ($this->isReadable()) {
            $this->contents = @include $this->getPath();
            return $this;
        } else {
            throw new FileNotReadable();
        }
    }

}
