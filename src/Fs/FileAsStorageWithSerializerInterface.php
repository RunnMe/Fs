<?php

namespace Runn\Fs;

use Runn\Serialization\SerializerAwareInterface;

/**
 * File as a Storage, with data serialization
 *
 * Interface FileAsStorageWithSerializerInterface
 * @package Runn\Fs
 */
interface FileAsStorageWithSerializerInterface
    extends FileAsStorageInterface, SerializerAwareInterface
{}