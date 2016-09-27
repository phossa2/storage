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

use Phossa2\Storage\Exception\LogicException;

/**
 * MountableInterface
 *
 * Able to mount/umount filesystems to the storage
 *
 * @package Phossa2\Storage
 * @author  Hong Zhang <phossa@126.com>
 * @version 2.0.0
 * @since   2.0.0 added
 */
interface MountableInterface
{
    /**
     * Mount a filesystem to the mount point
     *
     * @param  string $mountPoint '/' or '/usr' etc.
     * @param  FilesystemInterface $filesystem
     * @return bool mounting status
     * @throws LogicException if mount point exists already
     * @access public
     * @api
     */
    public function mount(
        /*# string */ $mountPoint,
        FilesystemInterface $filesystem
    )/*# : bool */;

    /**
     * Umounting a filesystem from the mount point
     *
     * @param  string $mountPoint '/' or '/usr' etc.
     * @return bool umounting status
     * @throws LogicException if not a valid mount point
     * @access public
     * @api
     */
    public function umount(/*# string */ $mountPoint)/*# : bool */;
}
