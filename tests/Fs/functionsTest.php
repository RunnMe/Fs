<?php

namespace Runn\tests\Fs\functions;

use function Runn\Fs\canCp;
use function Runn\Fs\canXcopy;
use function Runn\Fs\isMacos;
use function Runn\Fs\isWindows;

class functionsTest extends \PHPUnit_Framework_TestCase
{

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

}