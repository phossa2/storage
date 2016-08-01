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
use Phossa2\Storage\Traits\DriverAwareTrait;
use Phossa2\Storage\Interfaces\DriverInterface;
use Phossa2\Storage\Traits\PermissionAwareTrait;
use Phossa2\Storage\Interfaces\FilesystemInterface;
use Phossa2\Storage\Exception\BadMethodCallException;
use Phossa2\Storage\Interfaces\PermissionAwareInterface;

/**
 * Filesystem
 *
 * Implementation of FilesystemInterface
 *
 * @package Phossa2\Storage
 * @author  Hong Zhang <phossa@126.com>
 * @see     ObjectAbstract
 * @see     FilesystemInterface
 * @version 2.0.0
 * @since   2.0.0 added
 */
class Filesystem extends ObjectAbstract implements FilesystemInterface
{
    use PermissionAwareTrait, DriverAwareTrait;

    /**
     * @param  DriverInterface $driver
     * @param  int $permissions filesystem permissions
     * @access public
     * @api
     */
    public function __construct(
        DriverInterface $driver,
        int $permissions = PermissionAwareInterface::PERM_ALL
    ) {
        // set underlying driver
        $this->setDriver($driver);

        // set permissions for THIS filesystem
        $this->setPermissions($permissions);
    }

    /**
     * Forward to the driver
     *
     * @param  string $method
     * @param  array $args
     * @throws BadMethodCallException if method not found
     * @access public
     */
    public function __call(/*# string */ $method, array $args)
    {
        $driver = $this->getDriver();
        if (method_exists($driver, $method)) {
            return call_user_func_array([$driver, $method], $args);
        }

        throw new BadMethodCallException(
            Message::get(Message::MSG_METHOD_NOTFOUND, $method),
            Message::MSG_METHOD_NOTFOUND
        );
    }
}
