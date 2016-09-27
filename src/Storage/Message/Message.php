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

    /*
     * Create directory "%s" failed
     */
    const STR_MKDIR_FAIL = 1607250956;

    /*
     * Read directory "%s" failed
     */
    const STR_READDIR_FAIL = 1607250957;

    /*
     * Open stream "%s" failed
     */
    const STR_OPENSTREAM_FAIL = 1607250958;

    /*
     * Read file "%s" failed
     */
    const STR_READFILE_FAIL = 1607250959;

    /*
     * Get meta for file "%s" failed
     */
    const STR_GETMETA_FAIL = 1607250960;

    /*
     * Write file "%s" failed
     */
    const STR_WRITEFILE_FAIL = 1607250961;

    /*
     * Rename "%s" to "%s" failed
     */
    const STR_RENAME_FAIL = 1607250962;

    /*
     * Set meta for file "%s" failed
     */
    const STR_SETMETA_FAIL = 1607250963;

    /*
     * Copy "%s" to "%s" failed
     */
    const STR_COPY_FAIL = 1607250964;

    /*
     * Delete "%s" failed
     */
    const STR_DELETE_FAIL = 1607250965;

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
        self::STR_MKDIR_FAIL => 'Create directory "%s" failed',
        self::STR_READDIR_FAIL => 'Read directory "%s" failed',
        self::STR_OPENSTREAM_FAIL => 'Open stream "%s" failed',
        self::STR_READFILE_FAIL => 'Read file "%s" failed',
        self::STR_GETMETA_FAIL => 'Get meta for file "%s" failed',
        self::STR_WRITEFILE_FAIL => 'Write file "%s" failed',
        self::STR_RENAME_FAIL => 'Rename "%s" to "%s" failed',
        self::STR_SETMETA_FAIL => 'Set meta for file "%s" failed',
        self::STR_COPY_FAIL => 'Copy "%s" to "%s" failed',
        self::STR_DELETE_FAIL => 'Delete "%s" failed',
    ];
}
