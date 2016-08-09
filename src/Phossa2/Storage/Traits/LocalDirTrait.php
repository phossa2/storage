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

use Phossa2\Storage\Message\Message;

/**
 * LocalDirTrait
 *
 * Dealing with directories in LocalDriver
 *
 * @package Phossa2\Storage
 * @author  Hong Zhang <phossa@126.com>
 * @version 2.0.0
 * @since   2.0.0 added
 */
trait LocalDirTrait
{
    /**
     * {@inheritDoc}
     */
    protected function isRealDir(/*# string */ $realPath)/*# : bool */
    {
        return is_dir($realPath);
    }

    /**
     * {@inheritDoc}
     */
    protected function readDir(
        /*# string */ $realPath,
        /*# string */ $prefix = ''
    )/*# : array */ {
        try {
            $res = [];
            foreach (new \DirectoryIterator($realPath) as $fileInfo) {
                if($fileInfo->isDot()) continue;
                $res[] = $prefix . $fileInfo->getFilename();
            }
            return $res;

        } catch (\Exception $e) {
            $this->setError(
                Message::get(Message::STR_READDIR_FAIL, $realPath),
                Message::STR_READDIR_FAIL
            );
            return [];
        }
    }

    /**
     * {@inheritDoc}
     */
    protected function renameDir(
        /*# string */ $from,
        /*# string */ $to
    )/*# : bool */ {
        return @rename($from, $to);
    }

    /**
     * {@inheritDoc}
     */
    protected function copyDir(
        /*# string */ $from,
        /*# string */ $to
    )/*# : bool */ {
        $this->makeDirectory($to);
        foreach ($this->readDir($from) as $file) {
            $f = $from . \DIRECTORY_SEPARATOR . $file;
            $t = $to . \DIRECTORY_SEPARATOR . $file;
            $res = is_dir($f) ? $this->copyDir($f, $t) : @copy($f, $t);
            if (false === $res) {
                return $this->setError(
                    Message::get(Message::STR_COPY_FAIL, $f),
                    Message::STR_COPY_FAIL
                );
            }
        }
        return $res;
    }

    /**
     * {@inheritDoc}
     */
    protected function deleteDir(
        /*# string */ $realPath,
        /*# bool */ $keep = false
    )/*# : bool */ {
        $pref = rtrim($realPath, '/\\') . \DIRECTORY_SEPARATOR;
        foreach ($this->readDir($realPath, $pref) as $file) {
            $res = is_dir($file) ? $this->deleteDir($file) : unlink($file);
            if (false === $res) {
                return $this->setError(
                    Message::get(Message::STR_DELETE_FAIL, $file),
                    Message::STR_DELETE_FAIL
                );
            }
        }
        return $keep ? true : rmdir($realPath);
    }

    /**
     * {@inheritDoc}
     */
    protected function makeDirectory(/*# string */ $realPath)/*# : bool */
    {
        if (!is_dir($realPath)) {
            $umask = umask(0);
            @mkdir($realPath, 0755, true);
            umask($umask);

            if (!is_dir($realPath)) {
                $this->setError(
                    Message::get(Message::STR_MKDIR_FAIL, $realPath),
                    Message::STR_MKDIR_FAIL
                );
                return false;
            }
        }
        return true;
    }

    abstract public function setError(
        /*# string */ $message = '',
        /*# string */ $code = ''
    )/*# : bool */;
}