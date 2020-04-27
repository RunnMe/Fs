<?php

namespace Runn\tests\Fs\Files\JsonFile;

use PHPUnit\Framework\TestCase;
use Runn\Fs\Files\JsonFile;
use Runn\Serialization\Serializers\PassThru;

class JsonFileTest extends TestCase
{

    public function testSetSerializer()
    {
        $this->expectException(\BadMethodCallException::class);
        $file = new JsonFile();
        $file->setSerializer(new PassThru());
    }

    public function testSave()
    {
        $filename = sys_get_temp_dir() . '/FsTest_save.php';

        $file = new JsonFile($filename);
        $file->set(42)->save();
        $this->assertSame('42', file_get_contents($filename));

        $file = new JsonFile($filename);
        $file->set('foo')->save();
        $this->assertSame('"foo"', file_get_contents($filename));

        $file = new JsonFile($filename);
        $file->set([1, 2, 3])->save();
        $this->assertSame('[1,2,3]', file_get_contents($filename));

        $file = new JsonFile($filename);
        $file->set(['a'=>42, 'b'=>'foo', 'c'=>[1, 2, 3]])->save();
        $this->assertSame('{"a":42,"b":"foo","c":[1,2,3]}', file_get_contents($filename));

        unlink($filename);
    }

    public function testLoad()
    {
        $filename = sys_get_temp_dir() . '/FsTest_save.php';

        file_put_contents($filename, '42');
        $file = new JsonFile($filename);
        $contents = $file->load()->get();
        $this->assertSame(42, $contents);

        file_put_contents($filename, '"foo"');
        $file = new JsonFile($filename);
        $contents = $file->load()->get();
        $this->assertSame('foo', $contents);

        file_put_contents($filename, '[1,2,3]');
        $file = new JsonFile($filename);
        $contents = $file->load()->get();
        $this->assertSame([1, 2, 3], $contents);

        file_put_contents($filename, '{"a":42,"b":"foo","c":[1,2,3]}');
        $file = new JsonFile($filename);
        $contents = $file->load()->get();
        $this->assertSame(['a'=>42, 'b'=>'foo', 'c'=>[1, 2, 3]], $contents);

        unlink($filename);
    }

}
