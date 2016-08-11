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

use Phossa2\Storage\Interfaces\FilesystemInterface;
use Phossa2\Storage\Interfaces\FilesystemAwareInterface;
use Phossa2\Storage\Message\Message;

/**
 * FilesystemAwareTrait
 *
 * Implementation of FilesystemAwareInterface
 *
 * @package Phossa2\Storage
 * @author  Hong Zhang <phossa@126.com>
 * @see     FilesystemAwareInterface
 * @version 2.0.0
 * @since   2.0.0 added
 */
trait FilesystemAwareTrait
{
    /**
     * @var    FilesystemInterface
     * @access protected
     */
    protected $filesystem;

    /**
     * {@inheritDoc}
     */
    public function setFilesystem(FilesystemInterface $filesystem)
    {
        $this->filesystem = $filesystem;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getFilesystem()/*# : FilesystemInterface */
    {
        return $this->filesystem;
    }

    /**
     * Check filesystem readable or not
     *
     * @param  string $path path to check
     * @return bool
     * @access protected
     */
    protected function isFilesystemReadable(/*# string */ $path)/*# : bool */
    {
        if ($this->getFilesystem()->isReadable()) {
            return true;
        } else {
            return $this->setError(
                Message::get(Message::STR_FS_NONREADABLE, $path),
                Message::STR_FS_NONREADABLE
            );
        }
    }

    /**
     * Check filesystem writable or not
     *
     * @param  string $path path to check
     * @return bool
     * @access protected
     */
    protected function isFilesystemWritable(/*# string */ $path)/*# : bool */
    {
        if ($this->getFilesystem()->isWritable()) {
            return true;
        } else {
            return $this->setError(
                Message::get(Message::STR_FS_NONWRITABLE, $path),
                Message::STR_FS_NONWRITABLE
            );
        }
    }

    /**
     * Check filesystem file deletable or not
     *
     * @param  string $path path to check
     * @return bool
     * @access protected
     */
    protected function isFilesystemDeletable(/*# string */ $path)/*# : bool */
    {
        if ($this->getFilesystem()->isDeletable()) {
            return true;
        } else {
            return $this->setError(
                Message::get(Message::STR_FS_NONDELETABLE, $path),
                Message::STR_FS_NONDELETABLE
            );
        }
    }

    abstract public function setError(
        /*# string */ $message = '',
        /*# string */ $code = ''
    )/*# : bool */;
}
