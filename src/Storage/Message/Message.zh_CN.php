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
    Message::STR_FS_NONWRITABLE => '"%s" 上的文件系统不可写',
    Message::STR_FS_NONDELETABLE => '"%s" 上的文件系统不可删',
    Message::STR_MKDIR_FAIL => '创建目录 "%s" 失败',
    Message::STR_READDIR_FAIL => '读取目录  "%s" 失败',
    Message::STR_OPENSTREAM_FAIL => '打开流  "%s" 失败',
    Message::STR_READFILE_FAIL => '读取文件  "%s" 失败',
    Message::STR_GETMETA_FAIL => '读取文件 "%s" 元数据失败',
    Message::STR_WRITEFILE_FAIL => '写入文件  "%s" 失败',
    Message::STR_RENAME_FAIL => '重新命名  "%s" 为  "%s" 失败',
    Message::STR_SETMETA_FAIL => '写入文件 "%s" 元数据失败',
    Message::STR_COPY_FAIL => '拷贝  "%s" 为  "%s" 失败',
    Message::STR_DELETE_FAIL => '删除 "%s" 失败',
];
