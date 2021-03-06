<?php

namespace Phossa2\Storage;

use Phossa2\Storage\Driver\LocalDriver;
use Phossa2\Storage\Interfaces\PermissionAwareInterface;

/**
 * Storage test case.
 */
class StorageTest extends \PHPUnit_Framework_TestCase
{
    private $object;
    private $dir;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->dir = sys_get_temp_dir() . \DIRECTORY_SEPARATOR .
            rand(100, 999) . microtime(true);

        $this->object = new Storage(
            '/',
            new Filesystem(new LocalDriver($this->dir))
        );
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        $this->object = null;
        usleep(100000);
        @rmdir($this->dir);
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
     * Test has()
     *
     * @cover Phossa2\Storage\Storage::has()
     * @cover Phossa2\Storage\Storage::put()
     * @cover Phossa2\Storage\Storage::del()
     */
    public function testHas()
    {
        // not found
        $this->assertFalse($this->object->has('/bingo'));

        // found
        $this->assertTrue($this->object->put('/bingo', 'wow'));
        $this->assertTrue($this->object->has('/bingo'));

        // clear
        $this->assertTrue($this->object->del('/bingo'));

        // not found
        $this->assertFalse($this->object->has('/bingo'));
    }

    /**
     * Test get not found
     *
     * @cover Phossa2\Storage\Storage::get()
     */
    public function testGet0()
    {
        // not found
        $this->assertEquals(null, $this->object->get('/bingo'));

        $this->expectOutputString('Path "/bingo" not found');

        echo $this->object->getError();
    }

    /**
     * Test get normal file
     *
     * @cover Phossa2\Storage\Storage::get()
     * @cover Phossa2\Storage\Storage::put()
     * @cover Phossa2\Storage\Storage::del()
     */
    public function testGet1()
    {
        // not found
        $this->assertEquals(null, $this->object->get('/bingo'));

        // found
        $this->assertTrue($this->object->put('/bingo', 'wow'));
        $this->assertEquals('wow', $this->object->get('/bingo'));

        // clear
        $this->assertTrue($this->object->del('/bingo'));

        // deleted
        $this->assertEquals(null, $this->object->get('/bingo'));
    }

    /**
     * Test get dir
     *
     * @cover Phossa2\Storage\Storage::get()
     * @cover Phossa2\Storage\Storage::put()
     * @cover Phossa2\Storage\Storage::del()
     */
    public function testGet2()
    {
        // not found
        $this->assertEquals(null, $this->object->get('/b1'));

        // dir
        $this->assertTrue($this->object->put('/b1/b2', 'wow'));
        $this->assertTrue($this->object->put('/b1/b3', 'wow'));
        $this->assertTrue($this->object->put('/b1/b4/b5', 'wow'));

        $this->assertEquals(3, sizeof($this->object->get('/b1')));

        // check file in sub dir
        $this->assertEquals('wow', $this->object->get('/b1/b4/b5'));

        // delete one sub dir
        $this->assertTrue($this->object->del('/b1/b4'));
        $this->assertEquals(null, $this->object->get('/b1/b4/b5'));

        $this->assertEquals('wow', $this->object->get('/b1/b3'));

        $this->assertEquals(2, sizeof($this->object->get('/b1')));

        // clear
        $this->assertTrue($this->object->del('/b1'));

        // deleted
        $this->assertEquals(null, $this->object->get('/b1'));
    }

    /**
     * Test get stream
     *
     * @cover Phossa2\Storage\Storage::get()
     * @cover Phossa2\Storage\Storage::put()
     * @cover Phossa2\Storage\Storage::del()
     */
    public function testGet3()
    {
        // not found
        $this->assertEquals(null, $this->object->get('/bingo'));

        // put
        $this->assertTrue($this->object->put('/bingo', 'wow'));

        // get stream
        $stream = $this->object->get('/bingo', true);
        $this->assertTrue(is_resource($stream));

        // clear
        fclose($stream);
        $this->assertTrue($this->object->del('/bingo'));

        // check again
        $this->assertEquals(null, $this->object->get('/bingo'));
    }

    /**
     * Test normal put
     *
     * @cover Phossa2\Storage\Storage::put()
     */
    public function testPut0()
    {
        // put
        $this->assertTrue($this->object->put('/bingo', 'wow'));

        // test
        $this->assertEquals('wow', $this->object->get('/bingo'));

        // clear
        $this->assertTrue($this->object->del('/bingo'));
    }

    /**
     * Test put stream
     *
     * @cover Phossa2\Storage\Storage::put()
     */
    public function testPut1()
    {
        // put
        $this->assertTrue($this->object->put('/bingo', 'wow'));

        // get/put stream
        $stream = $this->object->get('/bingo', true);
        $this->assertTrue($this->object->put('/bingo2', $stream));
        fclose($stream);

        $this->assertEquals('wow', $this->object->get('/bingo2'));

        // clear
        $this->assertTrue($this->object->del('/bingo'));
        $this->assertTrue($this->object->del('/bingo2'));

        $this->assertEquals(null, $this->object->get('/bingo'));
        $this->assertEquals(null, $this->object->get('/bingo2'));
    }

    /**
     * Test put meta
     *
     * @cover Phossa2\Storage\Storage::put()
     */
    public function testPut2()
    {
        // put
        $this->assertTrue($this->object->put('/bingo', 'wow', ['mtime' => 10]));

        $meta = $this->object->meta('/bingo');
        $this->assertEquals(10, $meta['mtime']);

        // change meta
        $this->assertTrue($this->object->put('/bingo', null, ['mtime' => 20]));

        $meta = $this->object->meta('/bingo');
        $this->assertEquals(20, $meta['mtime']);

        // clear
        $this->assertTrue($this->object->del('/bingo'));
        $this->assertEquals(null, $this->object->get('/bingo'));
    }

    /**
     * Test overwrite existing directory
     *
     * @cover Phossa2\Storage\Storage::put()
     */
    public function testPut3()
    {
        // put
        $this->assertTrue($this->object->put('/b1/b2', 'wow'));

        // overwrite existing dir, failed
        $this->assertFalse($this->object->put('/b1', 'xxx'));

        $this->assertTrue($this->object->del('/b1'));
    }

    /**
     * Test overwrite existing file
     *
     * @cover Phossa2\Storage\Storage::put()
     */
    public function testPut4()
    {
        $this->assertTrue($this->object->put('/b1', 'wow'));
        $this->assertFalse($this->object->put('/b1/b2', 'wow'));

        $this->assertEquals('wow', $this->object->get('/b1'));

        $this->assertTrue($this->object->del('/b1'));
    }

    /**
     * Test same filesystem file copy
     *
     * @cover Phossa2\Storage\Storage::copy()
     */
    public function testCopy1()
    {
        // put
        $this->assertTrue($this->object->put('/bingo', 'wow'));
        $this->assertTrue($this->object->put('/bingo2', 'wow2'));

        // copy 1
        $this->assertTrue($this->object->copy('/bingo', '/bb/bingo2'));
        $this->assertEquals('wow', $this->object->get('/bb/bingo2'));

        // overwrite copy
        $this->assertTrue($this->object->copy('/bingo2', '/bb/bingo2'));
        $this->assertEquals('wow2', $this->object->get('/bb/bingo2'));

        // copy with trailing slash
        $this->assertTrue($this->object->copy('/bingo2', '/bx/'));
        $this->assertEquals('wow2', $this->object->get('/bx/bingo2'));

        // clear
        $this->assertTrue($this->object->del('/bingo'));
        $this->assertTrue($this->object->del('/bingo2'));
        $this->assertTrue($this->object->del('/bb'));
    }

    /**
     * Test same filesystem file copy into dir
     *
     * @cover Phossa2\Storage\Storage::copy()
     */
    public function testCopy10()
    {
        // put
        $this->assertTrue($this->object->put('/b1/b2', 'wow'));
        $this->assertTrue($this->object->put('/bingo', 'wow2'));

        // copy file into dir
        $this->assertTrue($this->object->copy('/bingo', '/b1'));

        $this->assertEquals(['/b1/b2', '/b1/bingo'], $this->object->get('/b1'));

        // clear
        $this->assertTrue($this->object->del('/bingo'));
        $this->assertTrue($this->object->del('/b1'));
    }

    /**
     * Test same filesystem dir copy
     *
     * @cover Phossa2\Storage\Storage::copy()
     */
    public function testCopy2()
    {
        // put
        $this->assertTrue($this->object->put('/b1/bingo1', 'wow1'));
        $this->assertTrue($this->object->put('/b1/b2/bingo2', 'wow2'));

        // copy
        $this->assertTrue($this->object->copy('/b1', '/b3'));

        $this->assertEquals('wow1', $this->object->get('/b3/bingo1'));
        $this->assertEquals('wow2', $this->object->get('/b3/b2/bingo2'));

        // clear
        $this->assertTrue($this->object->del('/b1'));
        $this->assertTrue($this->object->del('/b3'));
    }

    /**
     * Test same filesystem dir copy failure
     *
     * @cover Phossa2\Storage\Storage::copy()
     */
    public function testCopy20()
    {
        // put
        $this->assertTrue($this->object->put('/b1/bingo1', 'wow1'));
        $this->assertTrue($this->object->put('/b1/b2/bingo2', 'wow2'));
        $this->assertTrue($this->object->put('/b3', 'wow3'));

        // copy dir overto a existing file, failed
        $this->assertFalse($this->object->copy('/b1', '/b3'));

        // clear
        $this->assertTrue($this->object->del('/b1'));
        $this->assertTrue($this->object->del('/b3'));
    }

    /**
     * Test different filesystem file copy
     *
     * @cover Phossa2\Storage\Storage::copy()
     */
    public function testCopy3()
    {
        // mount another filesystem
        $dir = $this->dir . 'x1';
        $this->object->mount('/disk', new Filesystem($dir));

        // put
        $this->assertTrue($this->object->put('/bingo', 'wow'));

        // copy
        $this->assertTrue($this->object->copy('/bingo', '/disk/bingo'));

        $this->assertEquals('wow', $this->object->get('/disk/bingo'));

        // clear
        $this->assertTrue($this->object->del('/bingo'));
        $this->assertTrue($this->object->del('/disk'));

        @rmdir($dir);
    }

    /**
     * Test different filesystem file copy into dir
     *
     * @cover Phossa2\Storage\Storage::copy()
     */
    public function testCopy30()
    {
        // mount another filesystem
        $dir = $this->dir . 'x2';
        $this->object->mount('/disk', new Filesystem(new LocalDriver($dir)));

        // put
        $this->assertTrue($this->object->put('/bingo', 'wow'));
        $this->assertTrue($this->object->put('/disk/bingo/b1', 'wow'));

        // copy file into directory
        $this->assertTrue($this->object->copy('/bingo', '/disk/bingo'));

        $this->assertTrue($this->object->has('/disk/bingo/bingo'));
        $this->assertTrue($this->object->has('/disk/bingo/b1'));

        // clear
        $this->assertTrue($this->object->del('/bingo'));
        $this->assertTrue($this->object->del('/disk'));

        @rmdir($dir);
    }

    /**
     * Test different filesystem dir copy
     *
     * @cover Phossa2\Storage\Storage::copy()
     */
    public function testCopy4()
    {
        // mount another filesystem
        $dir = $this->dir . 'x3';
        $this->object->mount('/disk', new Filesystem(new LocalDriver($dir)));

        // put
        $this->assertTrue($this->object->put('/b1/bingo1', 'wow1'));
        $this->assertTrue($this->object->put('/b1/b2/bingo2', 'wow2'));

        // copy
        $this->assertTrue($this->object->copy('/b1', '/disk/b3'));

        $this->assertEquals('wow1', $this->object->get('/disk/b3/bingo1'));
        $this->assertEquals('wow2', $this->object->get('/disk/b3/b2/bingo2'));

        // clear
        $this->assertTrue($this->object->del('/b1'));
        $this->assertTrue($this->object->del('/disk/b3'));

        @rmdir($dir);
    }

    /**
     * Test different filesystem dir copy over file is NOT OK !!
     *
     * @cover Phossa2\Storage\Storage::copy()
     */
    public function testCopy40()
    {
        // mount another filesystem
        $dir = $this->dir . 'x4';
        $this->object->mount('/disk', new Filesystem(new LocalDriver($dir)));

        // put
        $this->assertTrue($this->object->put('/b1/bingo1', 'wow1'));
        $this->assertTrue($this->object->put('/b1/b2/bingo2', 'wow2'));
        $this->assertTrue($this->object->put('/disk/b3', 'wow3'));

        // copy dir over existing file is not OK
        $this->assertFalse($this->object->copy('/b1', '/disk/b3'));

        // clear
        $this->assertTrue($this->object->del('/b1'));
        $this->assertTrue($this->object->del('/disk'));

        @rmdir($dir);
    }

    /**
     * Test same filesystem file move
     *
     * @cover Phossa2\Storage\Storage::move()
     */
    public function testMove1()
    {
        // put
        $this->assertTrue($this->object->put('/bingo', 'wow'));
        $this->assertTrue($this->object->put('/bingo2', 'wow2'));

        // move
        $this->assertTrue($this->object->move('/bingo', '/bb/bingo2'));
        $this->assertFalse($this->object->has('/bingo'));
        $this->assertEquals('wow', $this->object->get('/bb/bingo2'));

        // overwrite move
        $this->assertTrue($this->object->move('/bingo2', '/bb/bingo2'));
        $this->assertFalse($this->object->has('/bingo2'));
        $this->assertEquals('wow2', $this->object->get('/bb/bingo2'));

        // clear
        $this->assertTrue($this->object->del('/bb'));
    }

    /**
     * Test same filesystem file move into dir
     *
     * @cover Phossa2\Storage\Storage::move()
     */
    public function testMove10()
    {
        // put
        $this->assertTrue($this->object->put('/bingo', 'wow'));
        $this->assertTrue($this->object->put('/bingo2', 'wow2'));

        // move
        $this->assertTrue($this->object->move('/bingo', '/bb/bingo2'));
        $this->assertFalse($this->object->has('/bingo'));
        $this->assertEquals('wow', $this->object->get('/bb/bingo2'));

        // move into a dir and overwrite
        $this->assertTrue($this->object->move('/bingo2', '/bb'));
        $this->assertFalse($this->object->has('/bingo2'));
        $this->assertEquals('wow2', $this->object->get('/bb/bingo2'));

        // clear
        $this->assertTrue($this->object->del('/bb'));
    }

    /**
     * Test same filesystem dir move
     *
     * @cover Phossa2\Storage\Storage::move()
     */
    public function testMove2()
    {
        // put
        $this->assertTrue($this->object->put('/b1/bingo1', 'wow1'));
        $this->assertTrue($this->object->put('/b1/b2/bingo2', 'wow2'));
        //$this->assertTrue($this->object->put('/b3', 'wow3'));

        // move, directory overwrite file is NOT ok
        $this->assertTrue($this->object->move('/b1', '/b3'));
        sleep(1);

        $this->assertFalse($this->object->has('/b1/bingo1'));
        $this->assertFalse($this->object->has('/b1/b2/bingo2'));

        $this->assertEquals('wow1', $this->object->get('/b3/bingo1'));
        $this->assertEquals('wow2', $this->object->get('/b3/b2/bingo2'));

        // clear
        $this->assertTrue($this->object->del('/'));
    }

    /**
     * Test different filesystem file move
     *
     * @cover Phossa2\Storage\Storage::move()
     */
    public function testMove3()
    {
        // mount another filesystem
        $dir = $this->dir . 'x5';
        $this->object->mount('/disk', new Filesystem(new LocalDriver($dir)));

        // put
        $this->assertTrue($this->object->put('/bingo', 'wow'));
        $this->assertTrue($this->object->put('/b1/b2/bingo', 'xxx'));

        // move
        $this->assertTrue($this->object->move('/bingo', '/disk/bingo'));

        $this->assertFalse($this->object->has('/bingo'));

        $this->assertEquals('wow', $this->object->get('/disk/bingo'));

        // move into a dir
        $this->assertTrue($this->object->move('/disk/bingo', '/b1/b2'));
        $this->assertEquals('wow', $this->object->get('/b1/b2/bingo'));

        // clear
        $this->assertTrue($this->object->del('/disk'));
        $this->assertTrue($this->object->del('/'));

        @rmdir($dir);
    }

    /**
     * Test different filesystem dir move
     *
     * @cover Phossa2\Storage\Storage::move()
     */
    public function testMove4()
    {
        // mount another filesystem
        $dir = $this->dir . 'x7';
        $this->object->mount('/disk', new Filesystem(new LocalDriver($dir)));

        // put
        $this->assertTrue($this->object->put('/b1/bingo1', 'wow1'));
        $this->assertTrue($this->object->put('/b1/b2/bingo2', 'wow2'));

        // move
        $this->assertTrue($this->object->move('/b1', '/disk/b3'));

        $this->assertEquals('wow1', $this->object->get('/disk/b3/bingo1'));
        $this->assertEquals('wow2', $this->object->get('/disk/b3/b2/bingo2'));

        // clear
        $this->assertTrue($this->object->del('/disk'));

        @rmdir($dir);
    }

    /**
     * Test move '/', will keep '/' undeleted
     *
     * @cover Phossa2\Storage\Storage::move()
     */
    public function testMove5()
    {
        // mount another filesystem
        $dir = $this->dir . 'x9';
        $this->object->mount('/disk', new Filesystem(new LocalDriver($dir)));

        // put in '/'
        $this->assertTrue($this->object->put('/b1', 'wow1'));
        $this->assertTrue($this->object->put('/b2/bingo2', 'wow2'));

        // move
        $this->assertTrue($this->object->move('/', '/disk/b3'));
        $this->assertEquals('wow1', $this->object->get('/disk/b3/b1'));

        // clear
        $this->assertTrue($this->object->del('/disk/b3'));

        @rmdir($dir);
    }

    /**
     * Test move loop, like move '/' to '/subdir' is a failure
     *
     * @cover Phossa2\Storage\Storage::move()
     */
    public function testMove6()
    {
        // put in '/'
        $this->assertTrue($this->object->put('/b1', 'wow1'));
        $this->assertTrue($this->object->put('/b2/bingo2', 'wow2'));

        // recursive move failed
        $this->assertFalse($this->object->move('/', '/b3'));
        $this->assertTrue($this->object->has('/b1'));
        $this->assertTrue($this->object->has('/b2/bingo2'));

        $this->assertFalse($this->object->has('/b3'));

        // try sub
        $this->assertFalse($this->object->move('/b2', '/b2/new'));

        // clear
        $this->assertTrue($this->object->del('/'));
    }

    /**
     * Test permission
     *
     * @cover Phossa2\Storage\Storage::move()
     */
    public function testMove7()
    {
        // mount another filesystem READONLY
        $dir = $this->dir . 'x8';
        $this->object->mount('/disk', new Filesystem(
            new LocalDriver($dir), PermissionAwareInterface::PERM_READ
        ));

        // put
        $this->assertTrue($this->object->put('/bingo', 'wow1'));

        // write fail
        $this->assertFalse($this->object->put('/disk/b3', 'wow3'));

        // copy fail
        $this->assertFalse($this->object->copy('/bingo', '/disk'));

        // move fail
        $this->assertFalse($this->object->move('/bingo', '/disk'));

        // clear
        $this->assertTrue($this->object->del('/'));

        @rmdir($dir);
    }
}
