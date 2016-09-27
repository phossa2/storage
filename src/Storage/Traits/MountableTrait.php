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

namespace Phossa2\Storage\Traits;

use Phossa2\Storage\Message\Message;
use Phossa2\Storage\Exception\LogicException;
use Phossa2\Storage\Interfaces\MountableInterface;
use Phossa2\Storage\Interfaces\FilesystemInterface;

/**
 * MountableTrait
 *
 * Managing the mout points of the storage.
 *
 * @package Phossa2\Storage
 * @author  Hong Zhang <phossa@126.com>
 * @see     MountableInterface
 * @version 2.0.0
 * @since   2.0.0 added
 */
trait MountableTrait
{
    /**
     * filesystem map
     *
     * @var    FilesystemInterface[]
     * @access protected
     */
    protected $filesystems = [];

    /**
     * {@inheritDoc}
     */
    public function mount(
        /*# string */ $mountPoint,
        FilesystemInterface $filesystem
    )/*# : bool */ {
        // normalize mount point
        $mp = $this->cleanMountPoint($mountPoint);

        // mounted already
        if (isset($this->filesystems[$mp])) {
            throw new LogicException(
                Message::get(Message::STR_MOUNT_EXISTS, $mountPoint),
                Message::STR_MOUNT_EXISTS
            );
        }

        $this->filesystems[$mp] = $filesystem;
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function umount(/*# string */ $mountPoint)/*# : bool */
    {
        // normalize mount point
        $mp = $this->cleanMountPoint($mountPoint);

        // not mounted
        if (!isset($this->filesystems[$mp])) {
            throw new LogicException(
                Message::get(Message::STR_MOUNT_NOT_EXISTS, $mountPoint),
                Message::STR_MOUNT_NOT_EXISTS
            );
        }

        // umount now
        unset($this->filesystems[$mp]);

        return true;
    }

    /**
     * Clean path to standard mount point
     *
     * @param  string $path
     * @return string
     * @access protected
     */
    protected function cleanMountPoint(/*# string */ $path)/*# : string */
    {
        return '/' . trim($path, " \t\r\n/");
    }

    /**
     * Get the filesystem at mount point
     *
     * @param  string $mountPoint
     * @return FilesystemInterface
     * @access protected
     */
    protected function getFilesystemAt(
        /*# string */ $mountPoint
    )/*# : FilesystemInterface */ {
        return $this->filesystems[$mountPoint];
    }

    /**
     * Find mount point of the path
     *
     * @param  string $path
     * @return string
     * @access protected
     */
    protected function getMountPoint(/*# string */ $path)/*# : string */
    {
        while ($path !== '') {
            if (isset($this->filesystems[$path])) {
                return $path;
            }
            $path = substr($path, 0, strrpos($path, '/'));
        }
        return '/';
    }
}
