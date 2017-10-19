<?php

namespace Runn\tests\Fs\functions;

use function Runn\Fs\canCp;
use function Runn\Fs\isWindows;

class functionsTest extends \PHPUnit_Framework_TestCase
{

    public function testCanCp()
    {
        if (isWindows()) {
            $this->assertFalse(canCp());
        } else {
            $this->assertTrue(canCp());
        }
    }

}