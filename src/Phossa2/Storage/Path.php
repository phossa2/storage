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
use Phossa2\Storage\Traits\FilesystemAwareTrait;
use Phossa2\Shared\Extension\ExtensionAwareTrait;
use Phossa2\Storage\Interfaces\FilesystemInterface;
use Phossa2\Shared\Extension\ExtensionAwareInterface;
use Phossa2\Storage\Interfaces\FilesystemAwareInterface;
use Phossa2\Storage\Interfaces\DriverInterface;

/**
 * Path
 *
 * @package Phossa2\Storage
 * @author  Hong Zhang <phossa@126.com>
 * @see     ObjectAbstract
 * @see     PathInterface
 * @see     ErrorAwareInterface
 * @see     FilesystemAwareInterface
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
        // not exists or filesystem not readable
        if (!$this->exists() || !$this->isFilesystemReadable()) {
            return null;
        }

        $res = $this->getDriver()->getContent($this->path, $stream);
        $this->resetError();
        return $res;
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
        if ($this->isFilesystemWritable()) {
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
    public function isDir()/*# : bool */
    {
        if ($this->exists()) {
            return $this->getDriver()->isDir($this->path);
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
            if (!$this->isFilesystemDeletable()) {
                return false;
            }

            $res = $this->getDriver()->delete($this->path);
            $this->resetError();
            return $res;
        }
        return true;
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
     * Do copy or rename
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
        if (!$this->exists() || !$this->isFilesystemWritable()) {
            return false;
        }

        // destination is direcotry
        if ($this->getDriver()->isDir($destination)) {
            $destination .= '/' . basename($this->path);
        }

        $res = $this->getDriver()->{$action}($this->path, $destination);
        $this->resetError();
        return (bool) $res;
    }

    /**
     * Check filesystem readable or not
     *
     * @return bool
     * @access protected
     */
    protected function isFilesystemReadable()/*# : bool */
    {
        if ($this->getFilesystem()->isReadable()) {
            return true;
        } else {
            return $this->setError(
                Message::get(Message::STR_FS_NONREADABLE, $this->full),
                Message::STR_FS_NONREADABLE
            );
        }
    }

    /**
     * Check filesystem writable or not
     *
     * @return bool
     * @access protected
     */
    protected function isFilesystemWritable()/*# : bool */
    {
        if ($this->getFilesystem()->isWritable()) {
            return true;
        } else {
            return $this->setError(
                Message::get(Message::STR_FS_NONWRITABLE, $this->full),
                Message::STR_FS_NONWRITABLE
            );
        }
    }

    /**
     * Check filesystem file deletable or not
     *
     * @return bool
     * @access protected
     */
    protected function isFilesystemDeletable()/*# : bool */
    {
        if ($this->getFilesystem()->isDeletable()) {
            return true;
        } else {
            return $this->setError(
                Message::get(Message::STR_FS_NONDELETABLE, $this->full),
                Message::STR_FS_NONDELETABLE
            );
        }
    }
}
