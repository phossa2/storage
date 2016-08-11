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

use Phossa2\Storage\Message\Message;
use Phossa2\Shared\Base\ObjectAbstract;
use Phossa2\Shared\Error\ErrorAwareTrait;
use Phossa2\Shared\Error\ErrorAwareInterface;
use Phossa2\Storage\Interfaces\PathInterface;
use Phossa2\Storage\Interfaces\DriverInterface;
use Phossa2\Storage\Traits\FilesystemAwareTrait;
use Phossa2\Shared\Extension\ExtensionAwareTrait;
use Phossa2\Storage\Interfaces\FilesystemInterface;
use Phossa2\Shared\Extension\ExtensionAwareInterface;
use Phossa2\Storage\Interfaces\FilesystemAwareInterface;

/**
 * Path
 *
 * @package Phossa2\Storage
 * @author  Hong Zhang <phossa@126.com>
 * @see     ObjectAbstract
 * @see     PathInterface
 * @see     ErrorAwareInterface
 * @see     FilesystemAwareInterface
 * @see     ExtensionAwareInterface
 * @version 2.0.0
 * @since   2.0.0 added
 */
class Path extends ObjectAbstract implements PathInterface, ErrorAwareInterface, FilesystemAwareInterface, ExtensionAwareInterface
{
    use FilesystemAwareTrait, ErrorAwareTrait, ExtensionAwareTrait;

    /**
     * Full path with mount point
     *
     * @var    string
     * @access protected
     */
    protected $full;

    /**
     * relative path without mount point prefix
     *
     * @var    string
     * @access protected
     */
    protected $path;

    /**
     * Instantiate the path object
     *
     * @param  string $full full path
     * @param  string $path relative path without mount point
     * @param  FilesystemInterface $filesystem
     * @access public
     * @api
     */
    public function __construct(
        /*# string */ $full,
        /*# string */ $path,
        FilesystemInterface $filesystem
    ) {
        $this->setFilesystem($filesystem);
        $this->full = $full;
        $this->path = $path;
    }

    /**
     * {@inheritDoc}
     */
    public function exists()/*# : bool */
    {
        if (!$this->getDriver()->exists($this->path)) {
            return $this->setError(
                Message::get(Message::MSG_PATH_NOTFOUND, $this->full),
                Message::MSG_PATH_NOTFOUND
            );
        }
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function getContent(/*# bool */ $stream = false)
    {
        if ($this->exists() && $this->isFilesystemReadable($this->full)) {
            $res = $this->getDriver()->getContent($this->path, $stream);
            $this->resetError();
            return $res;
        }
        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function getMeta()/*# : array */
    {
        if (!$this->exists()) {
            return [];
        }

        $res = $this->getDriver()->getMeta($this->path);
        $this->resetError();
        return $res;
    }

    /**
     * {@inheritDoc}
     */
    public function getPath()/*# : string */
    {
        return $this->path;
    }

    /**
     * {@inheritDoc}
     */
    public function getFullPath()/*# : string */
    {
        return $this->full;
    }

    /**
     * {@inheritDoc}
     */
    public function setContent($content)/*# : bool */
    {
        if ($this->isFilesystemWritable($this->full) &&
            !$this->hasTrailingSlash($this->path)
        ) {
            $res = $this->getDriver()->setContent($this->path, $content);
            $this->resetError();
            return $res;
        }
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function setMeta(array $meta)/*# : bool */
    {
        if (!$this->exists()) {
            return false;
        }

        if (!empty($meta)) {
            $res = $this->getDriver()->setMeta($this->path, $meta);
            $this->resetError();
            return $res;
        }
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function isDir(/*# string */ $path = '')/*# : bool */
    {
        $p = $path ?: $this->path;
        if ($this->hasTrailingSlash($p) || $this->getDriver()->isDir($p)) {
            return true;
        }
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function rename(/*# string */ $destination)/*# : bool */
    {
        return $this->alterAction($destination, 'rename');
    }

    /**
     * {@inheritDoc}
     */
    public function copy(/*# string */ $destination)/*# : bool */
    {
        return $this->alterAction($destination, 'copy');
    }

    /**
     * {@inheritDoc}
     */
    public function delete()/*# : bool */
    {
        if ($this->exists()) {
            if (!$this->isFilesystemDeletable($this->full)) {
                return false;
            }

            $res = $this->getDriver()->delete($this->path);
            $this->resetError();
            return $res;
        }
        return true;
    }

    /**
     * Get the driver
     *
     * @return DriverInterface
     * @access protected
     */
    protected function getDriver()/*# : DriverInterface */
    {
        return $this->getFilesystem()->getDriver();
    }

    /**
     * Do copy or rename in same filesystem
     *
     * @param  string $destination
     * @param  string $action 'copy' or 'rename'
     * @return bool
     * @access protected
     */
    protected function alterAction(
        /*# string */ $destination,
        /*# string */ $action
    )/*# : bool */ {
        if (!$this->exists() || !$this->isFilesystemWritable($this->full)) {
            return false;
        }

        if ($this->isDir($destination)) {
            $destination = rtrim($destination, '/') . '/' . basename($this->path);
        }

        $res = $this->getDriver()->{$action}($this->path, $destination);
        $this->resetError();
        return (bool) $res;
    }

    /**
     * Is current path has trailing '/'
     *
     * @param  string $path
     * @return bool
     * @access protected
     */
    protected function hasTrailingSlash(/*# string */ $path)/*# : bool */
    {
        if ($path && '/' == $path[strlen($path)-1]) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Reset error to driver's error
     *
     * @access protected
     */
    protected function resetError()
    {
        $this->copyError($this->getFilesystem()->getDriver());
    }
}
