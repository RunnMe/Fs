<?php

namespace Runn\tests\Fs\functions;

use PHPUnit\Framework\TestCase;
use function Runn\Fs\canCp;
use function Runn\Fs\canXcopy;
use function Runn\Fs\isMacos;
use function Runn\Fs\isWindows;
use function Runn\Fs\isLinux;

class functionsTest extends TestCase
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

}
