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

use Phossa2\Storage\Message\Message;

/*
 * Provide zh_CN translation
 *
 * @package Phossa2\Storage
 * @author  Hong Zhang <phossa@126.com>
 * @version 2.0.0
 * @since   2.0.0 added
 */
return [
    Message::STR_DRIVER_NOTFOUND => '存储驱动器没有设置',
    Message::STR_MOUNT_EXISTS => '装载点  "%s" 已存在',
    Message::STR_MOUNT_NOT_EXISTS => '装载点  "%s" 不存在',
    Message::STR_FS_NONREADABLE => '"%s" 上的文件系统不可读',
];
