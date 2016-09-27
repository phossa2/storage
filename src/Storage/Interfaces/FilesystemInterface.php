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
 * FilesystemInterface
 *
 * Consists of a specific driver and the global permissions for the driver
 *
 * @package Phossa2\Storage
 * @author  Hong Zhang <phossa@126.com>
 * @see     DriverAwareInterface
 * @see     PermissionAwareInterface
 * @version 2.0.0
 * @since   2.0.0 added
 */
interface FilesystemInterface extends DriverAwareInterface, PermissionAwareInterface
{
}
