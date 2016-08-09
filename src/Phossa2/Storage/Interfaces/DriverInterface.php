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

namespace Phossa2\Storage\Interfaces;

/**
 * DriverInterface
 *
 * @package Phossa2\Storage
 * @author  Hong Zhang <phossa@126.com>
 * @version 2.0.0
 * @since   2.0.0 added
 */
interface DriverInterface
{
    /**
     * Does this path exist ?
     *
     * @param  string $path
     * @return bool
     * @access public
     */
    public function exists(/*# string */ $path)/*# : bool */;

    /**
     * Get content or open stream from this path
     *
     * @param  string $path
     * @param  bool $stream
     * @return string|resource|array|null
     * @access public
     */
    public function getContent(/*# string */ $path, /*# bool */ $stream = false);

    /**
     * Get the meta
     *
     * @param  string $path
     * @return array
     * @access public
     */
    public function getMeta(/*# string */ $path)/*# : array */;

    /**
     * Write content to path
     *
     * @param  string $path
     * @param  string|resource $content
     * @return bool operation status
     * @access public
     */
    public function setContent(/*# string */ $path, $content)/*# : bool */;

    /**
     * Set meta data
     *
     * @param  string $path
     * @param  array $meta
     * @return bool operation status
     * @access public
     */
    public function setMeta(/*# string */ $path, array $meta)/*# : bool */;

    /**
     * Is path a directory ?
     *
     * @param  string $path
     * @return bool
     * @access public
     */
    public function isDir(/*# string */ $path)/*# : bool */;

    /**
     * Rename
     *
     * @param  string $from
     * @param  string $to
     * @access public
     */
    public function rename(/*# string */ $from, /*# string */ $to)/*# : bool */;

    /**
     * Copy
     *
     * @param  string $from
     * @param  string $to
     * @access public
     */
    public function copy(/*# string */ $from, /*# string */ $to)/*# : bool */;

    /**
     * Delete
     *
     * @param  string $path
     * @return bool
     * @access public
     */
    public function delete(/*# string */ $path)/*# : bool */;
}
