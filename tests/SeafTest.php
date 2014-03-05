<?php
namespace Seaf\Tests;

use Seaf;

class SeafTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateContainer()
    {
        $this->assertEquals('development', Seaf::getEnvMode() );
    }

}
