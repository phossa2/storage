<?php
/**
 * Phossa Project
 *
 * PHP version 5.4
 *
 * @category  Library
 * @package   Phossa2\Storage
 * @copyright Copyright (c) 2016 phossa.com
 * @license   http://mit-license.org/ MIT License
 * @link      http://www.phossa.com/
 */
/*# declare(strict_types=1); */

namespace Phossa2\Storage\Driver;

use Phossa2\Shared\Base\ObjectAbstract;
use Phossa2\Shared\Error\ErrorAwareTrait;
use Phossa2\Shared\Error\ErrorAwareInterface;
use Phossa2\Storage\Interfaces\DriverInterface;
use Phossa2\Shared\Extension\ExtensionAwareTrait;
use Phossa2\Shared\Extension\ExtensionAwareInterface;

/**
 * DriverAbstract
 *
 * @package Phossa2\Storage
 * @author  Hong Zhang <phossa@126.com>
 * @see     ObjectAbstract
 * @see     DriverInterface
 * @see     ErrorAwareInterface
 * @see     ExtensionAwareInterface
 * @version 2.0.0
 * @since   2.0.0 added
 */
abstract class DriverAbstract extends ObjectAbstract implements DriverInterface, ErrorAwareInterface, ExtensionAwareInterface
{
    use ErrorAwareTrait, ExtensionAwareTrait;

    /**
     * {@inheritDoc}
     */
    public function getContent(/*# string */ $path, /*# bool */ $stream = false)
    {
        if ($this->isDir($path)) {
            return $this->readDir($path);
        } elseif ($stream) {
            return $this->openStream($path);
        } else {
            return $this->readFile($path);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function rename(/*# string */ $from, /*# string */ $to)/*# : bool */
    {
        if ($this->isDir($from)) {
            return $this->renameDir($from, $to);
        } else {
            return $this->renameFile($from, $to);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function copy(/*# string */ $from, /*# string */ $to)/*# : bool */
    {
        if ($this->isDir($from)) {
            return $this->copyDir($from, $to);
        } else {
            return $this->copyFile($from, $to);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function delete(/*# string */ $path)/*# : bool */
    {
        if ($this->isDir($path)) {
            return $this->deleteDir($path);
        } else {
            return $this->deleteFile($path) && $this->deleteMeta($path);
        }
    }

    /**
     * Is path a directory
     *
     * @param  string $path
     * @return bool
     * @access protected
     */
    abstract protected function isDir(/*# string */ $path)/*# : bool */;

    /**
     * Read directory, returns an array of paths in this directory
     *
     * @param  string $path
     * @return array
     * @access protected
     */
    abstract protected function readDir(/*# string */ $path)/*# : array */;

    /**
     * Open stream
     *
     * @param  string $path
     * @return resource
     * @access protected
     */
    abstract protected function openStream(/*# string */ $path);

    /**
     * Read file and returns all the content
     *
     * @param  string $path
     * @return string
     * @access protected
     */
    abstract protected function readFile(/*# string */ $path)/*# : string */;

    /**
     * Delete directory
     *
     * @param  string $path
     * @return bool
     * @access protected
     */
    abstract protected function deleteDir(/*# string */ $path)/*# : bool */;

    /**
     * Delete the file
     *
     * @param  string $path
     * @return bool
     * @access protected
     */
    abstract protected function deleteFile(/*# string */ $path)/*# : bool */;

    /**
     * Delete the meta data (some drivers)
     *
     * @param  string $path
     * @return bool
     * @access protected
     */
    abstract protected function deleteMeta(/*# string */ $path)/*# : bool */;
}
