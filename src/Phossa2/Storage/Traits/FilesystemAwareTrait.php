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
    public function setFilesystem(FilesystemInterface $filesytem)
    {
        $this->filesystem = $filesytem;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getFilesystem()/*# : FilesystemInterface */
    {
        return $this->filesystem;
    }
}
