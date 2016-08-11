<?php

namespace Phossa2\Storage\Traits;

use Phossa2\Storage\Filesystem;
use Phossa2\Storage\Driver\LocalDriver;

/**
 * PathAwareTrait test case.
 */
class PathAwareTraitTest extends \PHPUnit_Framework_TestCase
{
    private $object;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        require_once __DIR__ . '/PathAware.php';

        $this->object = new PathAware();
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        $this->object = null;
        parent::tearDown();
    }

    /**
     * getPrivateProperty
     *
     * @param  string $propertyName
     * @return the property
     */
    public function getPrivateProperty($propertyName) {
        $reflector = new \ReflectionClass($this->object);
        $property  = $reflector->getProperty($propertyName);
        $property->setAccessible(true);

        return $property->getValue($this->object);
    }

    /**
     * Call protected/private method of a class.
     *
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    protected function invokeMethod($methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass($this->object);
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($this->object, $parameters);
    }

    /**
     * Test normalize
     *
     * @cover Phossa2\Storage\Traits\PathAwareTrait::normalize()
     */
    public function testNormalize()
    {
        $p1 = '/local/test/dir/../';
        $this->assertEquals('/local/test/', $this->invokeMethod('normalize', [$p1]));

        $p2 = '/local/./test/../dir';
        $this->assertEquals('/local/dir', $this->invokeMethod('normalize', [$p2]));
    }

    /**
     * Test mergePath
     *
     * @cover Phossa2\Storage\Traits\PathAwareTrait::mergePath()
     */
    public function testMergePath()
    {
        $p1 = '/local/test/';
        $p2 = '/bingo/wow/';
        $this->assertEquals(
            '/local/test/bingo/wow/',
            $this->invokeMethod('mergePath', [$p1, $p2])
        );

        $p1 = '/local/test';
        $p2 = 'bingo/wow';
        $this->assertEquals(
            '/local/test/bingo/wow',
            $this->invokeMethod('mergePath', [$p1, $p2])
        );
    }

    /**
     * Test splitPath
     *
     * @cover Phossa2\Storage\Traits\PathAwareTrait::splitPath()
     */
    public function testSplitPath()
    {
        $this->object->mount('/', new Filesystem(new LocalDriver('/test')));
        $this->object->mount('/usr', new Filesystem(new LocalDriver('/test/usr/')));
        $this->object->mount('/usr/bin', new Filesystem(new LocalDriver('/test/usr/bin')));

        $p1 = '/local/test';
        list($pref, $suff) = $this->invokeMethod('splitPath', [$p1]);
        $this->assertEquals('/', $pref);
        $this->assertEquals('local/test', $suff);


        $p1 = '/usr/bin/test';
        list($pref, $suff) = $this->invokeMethod('splitPath', [$p1]);
        $this->assertEquals('/usr/bin', $pref);
        $this->assertEquals('test', $suff);

        $p1 = '/usr/bin2/test';
        list($pref, $suff) = $this->invokeMethod('splitPath', [$p1]);
        $this->assertEquals('/usr', $pref);
        $this->assertEquals('bin2/test', $suff);
    }

    /**
     * Test path()
     *
     * @cover Phossa2\Storage\Traits\PathAwareTrait::path()
     */
    public function testPath()
    {
        $this->object->mount('/', new Filesystem(new LocalDriver('/test')));
        $this->object->mount('/usr', new Filesystem(new LocalDriver('/test/usr/')));
        $this->object->mount('/usr/bin', new Filesystem(new LocalDriver('/test/usr/bin')));

        $p1 = '/local/test';
        $path1 = $this->object->path($p1);
        $path2 = $this->object->path($p1);

        $this->assertTrue($path1 === $path2);
    }
}
