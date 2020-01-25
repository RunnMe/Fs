<?php

namespace Runn\tests\Fs\Files\PhpFile;

use PHPUnit\Framework\TestCase;
use Runn\Core\Std;
use Runn\Fs\Exceptions\EmptyPath;
use Runn\Fs\Exceptions\FileNotExists;
use Runn\Fs\Exceptions\FileNotReadable;
use Runn\Fs\Files\PhpFile;
use Runn\Serialization\Serializers\PassThru;

class PhpFileTest extends TestCase
{

    public function testSetSerializer()
    {
        $this->expectException(\BadMethodCallException::class);
        $file = new PhpFile();
        $file->setSerializer(new PassThru());
    }

    public function testLoadEmpty()
    {
        $this->expectException(EmptyPath::class);
        $file = new PhpFile();
        $file->load();
    }

    public function testLoadNotExists()
    {
        $this->expectException(FileNotExists::class);
        $file = new PhpFile(__DIR__ . '/This/File/Does/Not/Exist');
        $file->load();
    }

    public function testLoadNotReadable()
    {
        if (\Runn\Fs\isWindows()) {
            return;
        }

        $filename = tempnam(sys_get_temp_dir(), 'FsTest');
        chmod($filename, 0000);

        try {
            $file = new PhpFile($filename);
            $file->load();
        } catch (FileNotReadable $e) {
            $this->assertTrue(true);
            return;
        } finally {
            chmod($filename, 0777);
            unlink($filename);
        }

        $this->fail();
    }

    public function testLoad()
    {
        $filename = tempnam(sys_get_temp_dir(), 'FsTest');
        file_put_contents($filename, '<?php return 42;');

        $file = new PhpFile($filename);
        $file->load();

        $this->assertSame(42, $file->get());
    }

    public function testSave()
    {
        $filename = sys_get_temp_dir() . '/FsTest_create.php';
        $file = new PhpFile($filename);

        $file->set(42)->save();
        $this->assertEquals("<?php" . PHP_EOL . PHP_EOL . "return 42;", file_get_contents($filename));

        $file->set([1, 2, 'foo'])->save();
        $expected = <<<'SAVED'
<?php

return [
  0 => 1,
  1 => 2,
  2 => 'foo',
];
SAVED;
        $this->assertEquals(
            str_replace("\r\n", "\n", $expected),
            str_replace("\r\n", "\n", file_get_contents($filename))
        );

        $file->set(new Std(['foo' => 'bar', 'baz' => 12]))->save();
        $expected = <<<'SAVED'
<?php

return Runn\Core\Std::__set_state([
   '__data' =>
  [
    'foo' => 'bar',
    'baz' => 12,
  ],
]);
SAVED;
        $this->assertEquals(
            str_replace("\r\n", "\n", $expected),
            str_replace("\r\n", "\n", file_get_contents($filename))
        );

        unlink($filename);
    }

}
