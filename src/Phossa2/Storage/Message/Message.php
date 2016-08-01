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

namespace Phossa2\Storage\Message;

use Phossa2\Shared\Message\Message as BaseMessage;

/**
 * Message class for Phossa2\Storage
 *
 * @package Phossa2\Storage
 * @author  Hong Zhang <phossa@126.com>
 * @see     \Phossa2\Shared\Message\Message
 * @version 2.0.0
 * @since   2.0.0 added
 */
class Message extends BaseMessage
{
    /*
     * Storage driver not found
     */
    const STR_DRIVER_NOTFOUND = 1607250950;

    /*
     * Mount point "%s" exists already
     */
    const STR_MOUNT_EXISTS = 1607250951;

    /*
     * Mount point "%s" does not exist
     */
    const STR_MOUNT_NOT_EXISTS = 1607250952;

    /*
     * Filesystem "%s" not readable
     */
    const STR_FS_NONREADABLE = 1607250953;

    /*
     * Filesystem "%s" not writable
     */
    const STR_FS_NONWRITABLE = 1607250954;

    /*
     * Filesystem "%s" not deletable
     */
    const STR_FS_NONDELETABLE = 1607250955;

    /**
     * {@inheritDoc}
     */
    protected static $messages = [
        self::STR_DRIVER_NOTFOUND => 'Storage driver not found',
        self::STR_MOUNT_EXISTS => 'Mount point "%s" exists already',
        self::STR_MOUNT_NOT_EXISTS => 'Mount point "%s" does not exist',
        self::STR_FS_NONREADABLE => 'Filesystem "%s" not readable',
        self::STR_FS_NONWRITABLE => 'Filesystem "%s" not writable',
        self::STR_FS_NONDELETABLE => 'Filesystem "%s" not deletable',
    ];
}
