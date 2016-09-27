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
 * PermissionAwareInterface
 *
 * Checking owner permission only, reserved for future upgrades
 *
 * @package Phossa2\Storage
 * @author  Hong Zhang <phossa@126.com>
 * @version 2.0.0
 * @since   2.0.0 added
 */
interface PermissionAwareInterface
{
    /**
     * constants
     */
    const PERM_ALL    = 0666;
    const PERM_OTHER  = 0066;
    const PERM_READ   = 0400;
    const PERM_WRITE  = 0200;

    /**
     * Get current permissions
     *
     * @return int
     * @access public
     * @api
     */
    public function getPermissions()/*# : int */;

    /**
     * Set current permissions
     *
     * @param  int $permissions
     * @return $this
     * @access public
     * @api
     */
    public function setPermissions(/*# int */ $permissions);

    /**
     * Is readable ?
     *
     * @return bool
     * @access public
     * @api
     */
    public function isReadable()/*# : bool */;

    /**
     * Is  writable ?
     *
     * @return bool
     * @access public
     * @api
     */
    public function isWritable()/*# : bool */;

    /**
     * Is deletable ?
     *
     * @return bool
     * @access public
     * @api
     */
    public function isDeletable()/*# : bool */;
}
