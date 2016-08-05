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

namespace Phossa2\Storage\Driver;

use Phossa2\Storage\Message\Message;
use Phossa2\Storage\Exception\LogicException;

/**
 * LocalDriver
 *
 * @package Phossa2\Storage
 * @author  Hong Zhang <phossa@126.com>
 * @see     DriverAbstract
 * @version 2.0.0
 * @since   2.0.0 added
 */
class LocalDriver extends DriverAbstract
{
    /**
     * driver root
     *
     * @var    string
     * @access protected
     */
    protected $root;

    /**
     * @param  string $rootDir
     * @throws LogicException
     * @access public
     */
    public function __construct(/*# string */ $rootDir)
    {
        if (!$this->makeDirectory($rootDir)) {
            throw new LogicException(
                $this->getError(), $this->getErrorCode()
            );
        }
        $this->root = rtrim($rootDir, "/\\") . \DIRECTORY_SEPARATOR;
    }

    /**
     * {@inheritDoc}
     */
    protected function realPath(/*# string */ $path)/*# : string */
    {
        return $this->root . str_replace('/', \DIRECTORY_SEPARATOR, $path);
    }

    /**
     * {@inheritDoc}
     */
    protected function realExists(/*# string */ $realPath)/*# : bool */
    {
        return file_exists($realPath);
    }

    /**
     * {@inheritDoc}
     */
    protected function isDir(/*# string */ $realPath)/*# : bool */
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
        $res = [];
        try {
            foreach (new \DirectoryIterator($realPath) as $fileInfo) {
                if($fileInfo->isDot()) continue;
                $res[] = $prefix . $fileInfo->getFilename();
            }
        } catch (\Exception $e) {
            $this->setError(
                Message::get(Message::STR_READDIR_FAIL, $realPath),
                Message::STR_READDIR_FAIL
            );
        }
        return $res;
    }

    /**
     * {@inheritDoc}
     */
    protected function openReadStream(/*# string */ $realPath)
    {
        $stream = fopen($realPath, 'r');
        if (is_resource($stream)) {
            return $stream;
        } else {
            $this->setError(
                Message::get(Message::STR_OPENSTREAM_FAIL, $realPath),
                Message::STR_OPENSTREAM_FAIL
            );
            return null;
        }
    }

    /**
     * {@inheritDoc}
     */
    protected function readFile(/*# string */ $realPath)
    {
        $str = file_get_contents($realPath);

        if (false === $str) {
            $this->setError(
                Message::get(Message::STR_OPENSTREAM_FAIL, $realPath),
                Message::STR_OPENSTREAM_FAIL
            );
            return null;
        }

        return $str;
    }

    /**
     * {@inheritDoc}
     */
    protected function getRealMeta(/*# string */ $realPath)/*# : array */
    {
        try {
            $info = new \SplFileInfo($realPath);
            return [
                'type'  => $info->getType(),
                'size'  => $info->getSize(),
                'perm'  => $info->getPerms(),
                'ctime' => $info->getCTime(),
                'mtime' => $info->getMTime(),
            ];
        } catch (\Exception $e) {
            $this->setError(
                Message::get(Message::STR_GETMETA_FAIL, $realPath),
                Message::STR_GETMETA_FAIL
            );
            return [];
        }
    }

    /**
     * {@inheritDoc}
     */
    protected function fixPath(/*# string */ $realPath)/*# : bool */
    {
        $parent = dirname($realPath);
        return $this->makeDirectory($parent);
    }

    /**
     * {@inheritDoc}
     */
    protected function writeStream(
        /*# string */ $realPath,
        $resource
    )/*# : bool */ {
        $tmpfname = tempnam(dirname($realPath), 'FOO');
        $stream = fopen($tmpfname, 'w+b');

        if (is_resource($stream)) {
            stream_copy_to_stream($resource, $stream);
            return fclose($stream) && rename($tmpfname, $realPath);
        }
        return false;
    }

    /**
     * {@inheritDoc}
     */
    protected function writeFile(
        /*# string */ $realPath,
        /*# string */ $content
    )/*# : bool */ {
        // write to a temp file
        $tmpfname = tempnam(dirname($realPath), 'FOO');
        $handle = fopen($tmpfname, 'w');
        fwrite($handle, $content);
        fclose($handle);

        // rename to $realPath
        return rename($tmpfname, $realPath);
    }

    /**
     * Write meta data
     *
     * @param  string $realPath
     * @param  array $meta
     * @return bool
     * @access protected
     */
    protected function setRealMeta(
        /*# string */ $realPath,
        array $meta
    )/*# : bool */ {
        if (isset($meta['mtime'])) {
            touch($realPath, $meta['mtime']);
        }

        if (isset($meta['perm'])) {
            chmod($realPath, (int) $meta['perm']);
        }
        return true;
    }

    /**
     * {@inheritDoc}
     */
    protected function renameDir(
        /*# string */ $from,
        /*# string */ $to
    )/*# : bool */ {
        return rename($from, $to);
    }

    /**
     * {@inheritDoc}
     */
    protected function renameFile(
        /*# string */ $from,
        /*# string */ $to
    )/*# : bool */ {
        return rename($from, $to);
    }

    /**
     * {@inheritDoc}
     */
    protected function copyDir(
        /*# string */ $from,
        /*# string */ $to
    )/*# : bool */ {
        $files = $this->readDir($from);
        foreach ($files as $file) {
            $f = $from . \DIRECTORY_SEPARATOR . $file;
            $t = $to . \DIRECTORY_SEPARATOR . $file;
            if (is_dir($f)) {
                $res = $this->fixPath($t) && $this->copyDir($f, $t);
            } else {
                $res = $this->copyFile($f, $t);
            }
            if (false === $res) {
                return false;
            }
        }
        return true;
    }

    /**
     * {@inheritDoc}
     */
    protected function copyFile(
        /*# string */ $from,
        /*# string */ $to
    )/*# : bool */ {
        return copy($from, $to);
    }

    /**
     * {@inheritDoc}
     */
    protected function deleteDir(/*# string */ $realPath)/*# : bool */
    {
        $files = $this->readDir($realPath, $realPath);
        foreach ($files as $file) {
            if (is_dir($file)) {
                $res = $this->deleteDir($file);
            } else {
                $res = unlink($file);
            }
            if (false === $res) {
                return $this->setError(
                    Message::get(Message::STR_DELETE_FAIL, $file),
                    Message::STR_DELETE_FAIL
                );
            }
        }
        return rmdir($realPath);
    }

    /**
     * {@inheritDoc}
     */
    protected function deleteFile(/*# string */ $realPath)/*# : bool */
    {
        return unlink($realPath);
    }

    /**
     * create directory
     *
     * @param  string $dir
     * @access protected
     */
    protected function makeDirectory(/*# string */ $dir)/*# : bool */
    {
        if (!is_dir($dir)) {
            $umask = umask(0);
            mkdir($dir, 0755, true);
            umask($umask);

            if (!is_dir($dir)) {
                $this->setError(
                    Message::get(Message::STR_MKDIR_FAIL, $dir),
                    Message::STR_MKDIR_FAIL
                );
                return false;
            }
        }
        return true;
    }
}
