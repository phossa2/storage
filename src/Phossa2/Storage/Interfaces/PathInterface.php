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
 * PathInterface
 *
 * @package Phossa2\Storage
 * @author  Hong Zhang <phossa@126.com>
 * @version 2.0.0
 * @since   2.0.0 added
 */
interface PathInterface
{
    /**
     * Does this path exist ?
     *
     * @return bool
     * @access public
     * @api
     */
    public function exists()/*# : bool */;

    /**
     * Get content from the path
     *
     * @param  bool $stream open stream to get
     * @return string|array|resource|null
     * @access public
     * @api
     */
    public function getContent(/*# bool */ $stream = false);

    /**
     * Get meta data from the path
     *
     * @return array
     * @access public
     * @api
     */
    public function getMeta()/*# : array */;

    /**
     * Get the relative path
     *
     * @return string
     * @access public
     */
    public function getPath()/*# : string */;

    /**
     * Get the full path with mount point
     *
     * @return string
     * @access public
     */
    public function getFullPath()/*# : string */;

    /**
     * Set content of this path
     *
     * @param  string|resource $content
     * @return bool operation status
     * @access public
     * @api
     */
    public function setContent($content)/*# : bool */;

    /**
     * Update meta data
     *
     * @param  array $meta
     * @return bool operation status
     * @access public
     * @api
     */
    public function setMeta(array $meta)/*# : bool */;

    /**
     * Is $this->path or $path a directory
     *
     * @param  string $path path to check if not empty
     * @return bool
     * @access public
     * @api
     */
    public function isDir(/*# string */ $path = '')/*# : bool */;

    /**
     * rename to destination
     *
     * @param  string $destination
     * @return bool
     * @access public
     * @api
     */
    public function rename(/*# string */ $destination)/*# : bool */;

    /**
     * Copy to destination
     *
     * @param  string $destination
     * @return bool
     * @access public
     * @api
     */
    public function copy(/*# string */ $destination)/*# : bool */;

    /**
     * Delete this path
     *
     * @return bool
     * @access public
     */
    public function delete()/*# : bool */;
}
