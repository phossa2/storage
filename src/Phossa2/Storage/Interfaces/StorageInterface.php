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
 * @version 2.0.0
 * @since   2.0.0 added
 */
interface StorageInterface
{
    /**
     * Check a path exists or not
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
     * - if is file, returns the content
     * - if is dir, returns array of paths
     *
     * @param  string $path
     * @param  bool $stream read from stream
     * @return null|string|array|stream
     * @access public
     * @api
     */
    public function get(/*# string */ $path, /*# bool */ $stream = false);

    /**
     * Write content to the path
     *
     * @param  string $path
     * @param  string|resource $content
     * @param  array $meta extra meta data if any
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
     * Get meta data of the path
     *
     * @param  string $path
     * @return array
     * @access public
     * @api
     */
    public function meta(/*# string */ $path)/*# : array */;

    /**
     * Copy to a new path
     *
     * @param  string $from
     * @param  string $to
     * @param  bool $stream use $from as stream
     * @return bool operation status
     * @access public
     * @api
     */
    public function copy(
        /*# string */ $from,
        /*# string */ $to,
        /*# bool */ $stream = false
    )/*# : bool */;

    /**
     * Move to a new path
     *
     * @param  string $from
     * @param  string $to
     * @param  bool $stream use $from as stream
     * @return bool operation status
     * @access public
     * @api
     */
    public function move(
        /*# string */ $from,
        /*# string */ $to,
        /*# bool */ $stream = false
    )/*# : bool */;

    /**
     * Delete the specified path
     *
     * - If path is a link, delete the link only
     * - If path is a dir, recursively remove all
     *
     * @param  string $path
     * @return bool operation status
     * @access public
     * @api
     */
    public function delete(/*# string */ $path)/*# : bool */;
}
