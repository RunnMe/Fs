<?php

namespace Runn\tests\Fs\functions;

use function Runn\Fs\canCp;
use function Runn\Fs\canXcopy;
use Runn\Fs\Exceptions\CopyError;
use function Runn\Fs\isMacos;
use function Runn\Fs\isWindows;
use function Runn\Fs\isLinux;
use function Runn\Fs\cpFile;
use function Runn\Fs\xcopy;
use function Runn\Fs\copyFile;

class functionsTest extends \PHPUnit_Framework_TestCase
{
    private $tempDir;

    protected function delTree($dir)
    {
        if (empty($dir) || !is_dir($dir)) {
            return;
        }
        $iterator = new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new \RecursiveIteratorIterator($iterator, \RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($files as $file) {
            if ($file->isDir()) {
                rmdir($file->getPathname());
            } else {
                unlink($file->getPathname());
            }
        }
        rmdir($dir);
    }

    protected function setUp()
    {
        $this->tempDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('FsTest', false);
        $this->assertDirectoryNotExists($this->tempDir);
        mkdir($this->tempDir);
        mkdir($this->tempDir . DIRECTORY_SEPARATOR . 'source');
        mkdir($this->tempDir . DIRECTORY_SEPARATOR . 'target');

    }

    protected function tearDown()
    {
        $this->delTree($this->tempDir);
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
        if (canCp()) {
            file_put_contents($this->tempDir . '/source/cpFile.txt', 'TestCpFile');
            $src = $this->tempDir . '/source/cpFile.txt';

            // Copying a file in the same folder
            $dst = $this->tempDir . '/source/cpFileCopy.txt';
            $this->assertSame(0, cpFile($src, $dst));
            $this->assertFileEquals($src, $dst);
            $this->assertSame(fileowner($src), fileowner($dst));
            $this->assertSame(fileperms($src), fileperms($dst));

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
/*
    // Does not work on Linux
    public function testCpFileCopyDir()
    {
        if (!canCp()) {
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

    // Does not work on Linux
    public function testCpFileCopyToItself()
    {
        if (!canCp()) {
            return;
        }
        try {
            file_put_contents($this->tempDir . '/source/cpFile.txt', 'testCpFileCopyToItself');
            $src = $dst = $this->tempDir . '/source/cpFile.txt';
            cpFile($src, $dst);
        } catch (CopyError $e) {
            return;
        }
        $this->fail();
    }

    // Does not work on Linux
    public function testCpFileCopyNonexistingFile()
    {
        if (!canCp()) {
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
*/
    public function testXcopy()
    {
        if (canXcopy()) {
            file_put_contents($this->tempDir . '\source\xcopy.txt', 'TestXcopy');
            $src = $this->tempDir . '\source\xcopy.txt';

            // Copying a file from the source folder to the target folder
            $dst = $this->tempDir . '\target\\';
            $this->assertSame(0, xcopy($src, $dst));
            $this->assertFileEquals($src, $this->tempDir . '\target\xcopy.txt');

            // Copying a file from the source folder to the target folder and renaming the copy
            $dst = $this->tempDir . '\target\newXcopy.txt';
            $this->assertSame(0, xcopy($src, $dst));
            $this->assertFileEquals($src, $dst);

            // Copying a file from the source folder to an existing destination path
            file_put_contents($this->tempDir . '\source\xcopy.txt', 'Overwriting');
            $src = $this->tempDir . '\source\xcopy.txt';
            $dst = $this->tempDir . '\target\newXcopy.txt';
            $this->assertSame(0, xcopy($src, $dst));
            $this->assertFileEquals($src, $dst);

            // Copying all files and subfolders from the source folder to the target folder
            $src = $this->tempDir . '\source';
            mkdir($this->tempDir . '\source\testdir\subdir\test', 0777, true);
            mkdir($this->tempDir . '\newTarget');
            $dst = $this->tempDir . '\newTarget\\';
            $this->assertSame(0, xcopy($src, $dst));
            $this->assertDirectoryExists($dst . 'testdir\subdir\test');
            $this->assertFileExists($dst . 'xcopy.txt');
        }
    }

    public function testXcopyFileToSameFolder()
    {
        if (!canXcopy()) {
            return;
        }
        try {
            file_put_contents($this->tempDir . '\source\xcopy.txt', 'testXcopyFileToSameFolder');
            $src = $this->tempDir . '\source\xcopy.txt';
            $dst = $this->tempDir . '\source\xcopyCopy.txt';
            cpFile($src, $dst);
        } catch (CopyError $e) {
            return;
        }
        $this->fail();
    }

    public function testCopyFile()
    {
        file_put_contents($this->tempDir . '/source/copy.txt', 'TestCopy');
        $src = $this->tempDir . '/source/copy.txt';

        // Copying a file in the same folder
        $dst = $this->tempDir . '/source/copyCopy.txt';
        $this->assertTrue(copyFile($src, $dst));
        $this->assertFileEquals($src, $dst);

        // Copying a file from the source folder to the target folder
        $dst = $this->tempDir . '/target/copy.txt';
        $this->assertTrue(copyFile($src, $dst));
        $this->assertFileEquals($src, $dst);

        // Copying a file from the source folder to the target folder and renaming the copy
        $dst = $this->tempDir . '/target/newCopy.txt';
        $this->assertTrue(copyFile($src, $dst));
        $this->assertFileEquals($src, $dst);

        // Copying a file from the source folder to an existing destination path
        file_put_contents($this->tempDir . '/source/copy.txt', 'Overwriting');
        $src = $this->tempDir . '/source/copy.txt';
        $dst = $this->tempDir . '/target/newCopy.txt';
        $this->assertTrue(copyFile($src, $dst));
        $this->assertFileEquals($src, $dst);
    }

}
