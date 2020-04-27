<?php

namespace Runn\tests\Fs\Files\FileWithSerializer;

use PHPUnit\Framework\TestCase;
use Runn\Fs\Files\FileWithSerializer;
use Runn\Serialization\SerializerInterface;
use Runn\Serialization\Serializers\PassThru;

class FileWithSerializerTest extends TestCase
{

    public function testSetGetSerializer()
    {
        $file = new FileWithSerializer();

        $this->assertEquals(new PassThru(), $file->getSerializer());

        $serializer = new class implements SerializerInterface {
            public function encode($data): string
            {
            }

            public function decode(string $data)
            {
            }
        };
        $ret = $file->setSerializer($serializer);

        $this->assertSame($serializer, $file->getSerializer());
        $this->assertSame($ret, $file);
    }

    public function testNullSerializer()
    {
        $file = new FileWithSerializer();

        $afterLoadMethod = new \ReflectionMethod(get_class($file), 'processContentsAfterLoad');
        $afterLoadMethod->setAccessible(true);
        $beforeSaveMethod = new \ReflectionMethod(get_class($file), 'processContentsBeforeSave');
        $beforeSaveMethod->setAccessible(true);

        $content = 'Hello, world!';

        $this->assertSame($content, $afterLoadMethod->invoke($file, $content));
        $this->assertSame($content, $beforeSaveMethod->invoke($file, $content));
    }

    public function testSerializer()
    {
        $serializer = new class implements SerializerInterface {
            public function encode($data): string
            {
                return $data . '!!!';
            }

            public function decode(string $data)
            {
                return preg_replace('~!!!$~', '', $data);
            }
        };
        $file = new FileWithSerializer();
        $file->setSerializer($serializer);

        $afterLoadMethod = new \ReflectionMethod(get_class($file), 'processContentsAfterLoad');
        $afterLoadMethod->setAccessible(true);
        $beforeSaveMethod = new \ReflectionMethod(get_class($file), 'processContentsBeforeSave');
        $beforeSaveMethod->setAccessible(true);

        $content = 'Hello, world!!!';

        $this->assertSame('Hello, world', $afterLoadMethod->invoke($file, $content));
        $this->assertSame('Hello, world!!!!!!', $beforeSaveMethod->invoke($file, $content));
    }

}
