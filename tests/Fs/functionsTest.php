<?php

namespace Runn\tests\Fs\functions;

use function Runn\Fs\canCp;
use function Runn\Fs\canXcopy;
use Runn\Fs\Exceptions\CopyError;
use function Runn\Fs\isMacos;
use function Runn\Fs\isWindows;
use function Runn\Fs\isLinux;
use function Runn\Fs\cpFile;

class functionsTest extends \PHPUnit_Framework_TestCase
{
    private $tempDir;

    protected function setUp()
    {
        $this->tempDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('FsTest', false);
        $this->assertDirectoryNotExists($this->tempDir);
        mkdir($this->tempDir);
        mkdir($this->tempDir . '/source');
        mkdir($this->tempDir . '/target');
    }

    protected function tearDown()
    {
        array_map('unlink', glob($this->tempDir . '/source/*.*'));
        array_map('unlink', glob($this->tempDir . '/target/*.*'));
        array_map('rmdir', glob($this->tempDir . '/*'));
        rmdir($this->tempDir);
    }

    public function testIsWindows()
    {
        if ('\\' === DIRECTORY_SEPARATOR) {
            $this->assertTrue(isWindows());
        } else {
            $this->assertFalse(isWindows());
        }
    }

    public function testIsMacos()
    {
        if ('/' === DIRECTORY_SEPARATOR && 'Darwin' === trim(shell_exec('uname'))) {
            $this->assertTrue(isMacos());
        } else {
            $this->assertFalse(isMacos());
        }
    }

    public function testIsLinux()
    {
        if ('/' === DIRECTORY_SEPARATOR && 1 === preg_match('~(bsd|gnu|linux|dragonfly)~i',
                trim(shell_exec('uname')))) {
            $this->assertTrue(isLinux());
        } else {
            $this->assertFalse(isLinux());
        }
    }

    public function testCanCp()
    {
        if (isWindows()) {
            $this->assertFalse(canCp());
        } else {
            $this->assertTrue(canCp());
        }
    }

    public function testCanXcopy()
    {
        if (isWindows()) {
            $this->assertTrue(canXcopy());
        } else {
            $this->assertFalse(canXcopy());
        }
    }

    public function testCpFile()
    {
        if (!isWindows()) {
            file_put_contents($this->tempDir . '/source/cpFile.txt', 'TestCpFile');
            $src = $this->tempDir . '/source/cpFile.txt';

            // Copying a file in the same folder
            $dst = $this->tempDir . '/source/cpFileCopy.txt';
            $this->assertSame(0, cpFile($src, $dst));
            $this->assertFileEquals($src, $dst);

            // Copying a file from the source folder to the target folder
            $dst = $this->tempDir . '/target/';
            $this->assertSame(0, cpFile($src, $dst));
            $this->assertFileEquals($src, $this->tempDir . '/target/cpFile.txt');

            // Copying a file from the source folder to the target folder and renaming the copy
            $dst = $this->tempDir . '/target/newCpFile.txt';
            $this->assertSame(0, cpFile($src, $dst));
            $this->assertFileEquals($src, $dst);

            // Copying a file from the source folder to an existing destination path
            file_put_contents($this->tempDir . '/source/cpFile.txt', 'Overwriting');
            $src = $this->tempDir . '/source/cpFile.txt';
            $dst = $this->tempDir . '/target/newCpFile.txt';
            $this->assertSame(0, cpFile($src, $dst));
            $this->assertFileEquals($src, $dst);
        }
    }

    public function testCpFileCopyDir()
    {
        if (!isWindows()) {
            return;
        }
        try {
            $src = $this->tempDir . '/source/';
            $dst = $this->tempDir . '/target/';
            cpFile($src, $dst);
        } catch (CopyError $e) {
            return;
        }
        $this->fail();
    }

    public function testCpFileCopyToItself()
    {
        if (!isWindows()) {
            return;
        }
        try {
            $src = $dst = $this->tempDir . '/source/cpFile.txt';
            cpFile($src, $dst);
        } catch (CopyError $e) {
            return;
        }
        $this->fail();
    }

    public function testCpFileCopyNonexistingFile()
    {
        if (!isWindows()) {
            return;
        }
        try {
            $src = $this->tempDir . '/nonexistingFile';
            $dst = $this->tempDir . '/target/';
            cpFile($src, $dst);
        } catch (CopyError $e) {
            return;
        }
        $this->fail();
    }

}