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

namespace Phossa2\Storage;

use Phossa2\Shared\Base\ObjectAbstract;
use Phossa2\Shared\Error\ErrorAwareTrait;
use Phossa2\Storage\Interfaces\PathInterface;
use Phossa2\Storage\Traits\FilesystemAwareTrait;
use Phossa2\Storage\Interfaces\FilesystemInterface;

/**
 * Path
 *
 * @package Phossa2\Storage
 * @author  Hong Zhang <phossa@126.com>
 * @version 2.0.0
 * @since   2.0.0 added
 */
class Path extends ObjectAbstract implements PathInterface
{
    use FilesystemAwareTrait, ErrorAwareTrait;

    /**
     * relative path without the mount point prefix
     *
     * @var    string
     * @access protected
     */
    protected $path;

    /**
     * Instantiate the path object
     *
     * @param  string $path
     * @param  FilesystemInterface $filesystem
     * @access public
     * @api
     */
    public function __construct(
        /*# string */ $path,
        FilesystemInterface $filesystem
    ) {
        $this->setFilesystem($filesystem);
        $this->path = $path;
    }

    /**
     * Does this path exist ?
     *
     * @return bool
     * @access public
     * @api
     */
    public function exists()/*# : bool */
    {

    }

    /**
     * Write to the driver if updated
     *
     * @return bool write status
     * @access public
     * @api
     */
    public function write()/*# : bool */
    {

    }

    /**
     * Get content from the path
     *
     * @return string|PathInterface[]|null
     * @access public
     * @api
     */
    public function getContent()
    {

    }

    /**
     * Get meta data from the path
     *
     * @return array
     * @access public
     * @api
     */
    public function getMeta()/*# : array */
    {

    }

    /**
     * {@inheritDoc}
     */
    public function getPath()/*# : string */
    {
        return $this->path;
    }

    /**
     * Set content of this path before write
     *
     * @param  string $content
     * @return $this
     * @access public
     * @api
     */
    public function setContent(/*# string */ $content = '')
    {

    }

    /**
     * Update meta data (or partial)
     *
     * @param  array $meta
     * @return $this
     * @access public
     * @api
     */
    public function setMeta(array $meta)
    {

    }

    /**
     * Same filesystem rename
     *
     * @param  string $destination
     * @return bool
     * @access public
     * @api
     */
    public function rename(/*# : string */ $destination)/*# : bool */
    {

    }

    /**
     * Same filesystem copy
     *
     * @param  string $destination
     * @return bool
     * @access public
     * @api
     */
    public function copy(/*# : string */ $destination)/*# : bool */
    {

    }

    /**
     * Delete this path
     *
     * @return bool
     * @access public
     */
    public function delete()/*# : bool */
    {

    }
}
