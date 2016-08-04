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
 * FilesystemAwareInterface
 *
 * @package Phossa2\Storage
 * @author  Hong Zhang <phossa@126.com>
 * @version 2.0.0
 * @since   2.0.0 added
 */
interface FilesystemAwareInterface
{
    /**
     * @param  FilesystemInterface $filesystem
     * @return $this
     * @access public
     * @api
     */
    public function setFilesystem(FilesystemInterface $filesystem);

    /**
     * @return FilesystemInterface
     * @access public
     * @api
     */
    public function getFilesystem()/*# : FilesystemInterface */;
}
