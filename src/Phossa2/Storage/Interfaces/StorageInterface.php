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
 * StorageInterface
 *
 * @package Phossa2\Storage
 * @author  Hong Zhang <phossa@126.com>
 * @see     MountableInterface
 * @see     PathAwareInterface
 * @version 2.0.0
 * @since   2.0.0 added
 */
interface StorageInterface extends MountableInterface, PathAwareInterface
{
    /**
     * Check an absolute path exists or not
     *
     * @param  string $path
     * @return bool
     * @access public
     * @api
     */
    public function has(/*# string */ $path)/*# : bool */;

    /**
     * Read content from the path
     *
     * - if not found or not readable, returns NULL
     * - if is file, returns the STRING
     * - if is directory, returns ARRAY of paths
     *
     * @param  string $path
     * @param  bool $getAsStream return a stream to read from
     * @return null|string|array|resource
     * @access public
     * @api
     */
    public function get(/*# string */ $path, /*# bool */ $getAsStream = false);

    /**
     * Write content and meta data to the path
     *
     * @param  string $path
     * @param  string|resource|null $content
     * @param  array $meta meta data if any
     * @return bool operation status
     * @access public
     * @api
     */
    public function put(
        /*# string */ $path,
        $content,
        array $meta = []
    )/*# : bool */;

    /**
     * Delete the specified path
     *
     * - If path is a dir, recursively remove all
     *
     * @param  string $path
     * @return bool operation status
     * @access public
     * @api
     */
    public function del(/*# string */ $path)/*# : bool */;

    /**
     * Copy to a new path
     *
     * @param  string $from source path
     * @param  string $to destination path
     * @return bool operation status
     * @access public
     * @api
     */
    public function copy(
        /*# string */ $from,
        /*# string */ $to
    )/*# : bool */;

    /**
     * Move to a new path
     *
     * @param  string $from source path
     * @param  string $to destination path
     * @return bool operation status
     * @access public
     * @api
     */
    public function move(
        /*# string */ $from,
        /*# string */ $to
    )/*# : bool */;

    /**
     * Get meta data of the path。
     *
     * Returns empty [] if not found
     *
     * @param  string $path
     * @return array
     * @access public
     * @api
     */
    public function meta(/*# string */ $path)/*# : array */;
}
