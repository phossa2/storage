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

/**
 * NullDriver
 *
 * @package Phossa2\Storage
 * @author  Hong Zhang <phossa@126.com>
 * @see     DriverAbstract
 * @version 2.0.1
 * @since   2.0.0 added
 * @since   2.0.1 added makeDirectory()
 */
class NullDriver extends DriverAbstract
{
    /**
     * {@inheritDoc}
     */
    protected function realPath(/*# string */ $path)/*# : string */
    {
        return $path;
    }

    /**
     * {@inheritDoc}
     */
    protected function realExists(/*# string */ $realPath)/*# : bool */
    {
        return false;
    }

    /**
     * {@inheritDoc}
     */
    protected function isRealDir(/*# string */ $realPath)/*# : bool */
    {
        return false;
    }

    /**
     * {@inheritDoc}
     */
    protected function readDir(
        /*# string */ $realPath,
        /*# string */ $prefix = ''
    )/*# : array */ {
        return [];
    }

    /**
     * {@inheritDoc}
     */
    protected function makeDirectory(
        /*# string */ $realPath
    )/*# : bool */ {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    protected function openReadStream(/*# string */ $realPath)
    {
        return null;
    }

    /**
     * {@inheritDoc}
     */
    protected function readFile(/*# string */ $realPath)
    {
        return null;
    }

    /**
     * {@inheritDoc}
     */
    protected function getRealMeta(/*# string */ $realPath)/*# : array */
    {
        return [];
    }

    /**
     * {@inheritDoc}
     */
    protected function ensurePath(/*# string */ $realPath)/*# : bool */
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    protected function writeStream(
        /*# string */ $realPath,
        $resource
    )/*# : bool */ {
        return false;
    }

    /**
     * {@inheritDoc}
     */
    protected function writeFile(
        /*# string */ $realPath,
        /*# string */ $content
    )/*# : bool */ {
        return false;
    }

    /**
     * {@inheritDoc}
     */
    protected function setRealMeta(
        /*# string */ $realPath,
        array $meta
    )/*# : bool */ {
        return false;
    }

    /**
     * {@inheritDoc}
     */
    protected function renameDir(
        /*# string */ $from,
        /*# string */ $to
    )/*# : bool */ {
        return false;
    }

    /**
     * {@inheritDoc}
     */
    protected function renameFile(
        /*# string */ $from,
        /*# string */ $to
    )/*# : bool */ {
        return false;
    }

    /**
     * {@inheritDoc}
     */
    protected function copyDir(
        /*# string */ $from,
        /*# string */ $to
    )/*# : bool */ {
        return false;
    }

    /**
     * {@inheritDoc}
     */
    protected function copyFile(
        /*# string */ $from,
        /*# string */ $to
    )/*# : bool */ {
        return false;
    }

    /**
     * {@inheritDoc}
     */
    protected function deleteDir(/*# string */ $realPath)/*# : bool */
    {
        return false;
    }

    /**
     * {@inheritDoc}
     */
    protected function deleteFile(/*# string */ $realPath)/*# : bool */
    {
        return false;
    }
}
