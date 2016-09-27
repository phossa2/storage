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

use Phossa2\Storage\Interfaces\PermissionAwareInterface;

/**
 * PermissionAwareTrait
 *
 * Implementation of PermissionAwareInterface
 *
 * @package Phossa2\Storage
 * @author  Hong Zhang <phossa@126.com>
 * @see     PermissionAwareInterface
 * @version 2.0.0
 * @since   2.0.0 added
 */
trait PermissionAwareTrait
{
    /**
     * permissions
     *
     * @var    int
     * @access protected
     */
    protected $perm = PermissionAwareInterface::PERM_ALL;

    /**
     * {@inheritDoc}
     */
    public function getPermissions()/*# : int */
    {
        return $this->perm;
    }

    /**
     * {@inheritDoc}
     */
    public function setPermissions(/*# int */ $permissions)
    {
        $this->perm = $permissions;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function isReadable()/*# : bool */
    {
        return (bool) ($this->perm & PermissionAwareInterface::PERM_READ);
    }

    /**
     * {@inheritDoc}
     */
    public function isWritable()/*# : bool */
    {
        return (bool) ($this->perm & PermissionAwareInterface::PERM_WRITE);
    }

    /**
     * {@inheritDoc}
     */
    public function isDeletable()/*# : bool */
    {
        return (bool) ($this->perm & PermissionAwareInterface::PERM_WRITE);
    }
}
