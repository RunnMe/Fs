<?php

namespace Runn\Fs;

use Runn\Serialization\SerializerAwareInterface;

interface FileAsStorageWithSerializerInterface
    extends FileAsStorageInterface, SerializerAwareInterface
{

}