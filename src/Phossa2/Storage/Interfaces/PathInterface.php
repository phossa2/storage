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

use Phossa2\Shared\Error\ErrorAwareInterface;

/**
 * PathInterface
 *
 * @package Phossa2\Storage
 * @author  Hong Zhang <phossa@126.com>
 * @version 2.0.0
 * @since   2.0.0 added
 */
interface PathInterface extends FilesystemAwareInterface, ErrorAwareInterface
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
     * Write to the driver if updated
     *
     * @return bool write status
     * @access public
     * @api
     */
    public function write()/*# : bool */;

    /**
     * Get content from the path
     *
     * @return string|string[]|null
     * @access public
     * @api
     */
    public function getContent();

    /**
     * Get meta data from the path
     *
     * @return array
     * @access public
     * @api
     */
    public function getMeta()/*# : array */;

    /**
     * Get the path
     *
     * @return string
     * @access public
     */
    public function getPath()/*# : string */;

    /**
     * Set content of this path before write
     *
     * @param  string $content
     * @return $this
     * @access public
     * @api
     */
    public function setContent(/*# string */ $content = '');

    /**
     * Update meta data (or partial)
     *
     * @param  array $meta
     * @return $this
     * @access public
     * @api
     */
    public function setMeta(array $meta);

    /**
     * Same filesystem rename
     *
     * @param  string $destination
     * @return bool
     * @access public
     * @api
     */
    public function rename(/*# : string */ $destination)/*# : bool */;

    /**
     * Same filesystem copy
     *
     * @param  string $destination
     * @return bool
     * @access public
     * @api
     */
    public function copy(/*# : string */ $destination)/*# : bool */;

    /**
     * Delete this path
     *
     * @return bool
     * @access public
     */
    public function delete()/*# : bool */;
}
