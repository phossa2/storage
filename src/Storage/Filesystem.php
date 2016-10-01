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
use Phossa2\Storage\Driver\LocalDriver;
use Phossa2\Storage\Traits\DriverAwareTrait;
use Phossa2\Storage\Interfaces\DriverInterface;
use Phossa2\Storage\Traits\PermissionAwareTrait;
use Phossa2\Storage\Interfaces\FilesystemInterface;
use Phossa2\Storage\Interfaces\PermissionAwareInterface;

/**
 * Filesystem
 *
 * Implementation of FilesystemInterface. A wrapper of driver with permissions
 *
 * @package Phossa2\Storage
 * @author  Hong Zhang <phossa@126.com>
 * @see     ObjectAbstract
 * @see     FilesystemInterface
 * @version 2.1.0
 * @since   2.0.0 added
 * @since   2.1.0 modified `__construct` to accept string as first param
 */
class Filesystem extends ObjectAbstract implements FilesystemInterface
{
    use PermissionAwareTrait, DriverAwareTrait;

    /**
     * Set the driver and its global permissions
     *
     * @param  DriverInterface|string $driverOrPath driver or local path
     * @param  int $permissions filesystem permissions
     * @access public
     * @since  2.1.0 changed first param
     * @api
     */
    public function __construct(
        $driverOrPath,
        /*# int */ $permissions = PermissionAwareInterface::PERM_ALL
    ) {
        // path provided, init a LocalDriver
        if (is_string($driverOrPath)) {
            $driverOrPath = new LocalDriver($driverOrPath);
        }

        // set underlying driver
        $this->setDriver($driverOrPath);

        // set permissions for THIS filesystem
        $this->setPermissions($permissions);
    }
}
