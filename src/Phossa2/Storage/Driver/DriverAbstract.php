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

use Phossa2\Shared\Base\ObjectAbstract;
use Phossa2\Shared\Error\ErrorAwareTrait;
use Phossa2\Shared\Error\ErrorAwareInterface;
use Phossa2\Storage\Interfaces\DriverInterface;
use Phossa2\Shared\Extension\ExtensionAwareTrait;
use Phossa2\Shared\Extension\ExtensionAwareInterface;
use Phossa2\Storage\Message\Message;

/**
 * DriverAbstract
 *
 * @package Phossa2\Storage
 * @author  Hong Zhang <phossa@126.com>
 * @see     ObjectAbstract
 * @see     DriverInterface
 * @see     ErrorAwareInterface
 * @see     ExtensionAwareInterface
 * @version 2.0.0
 * @since   2.0.0 added
 */
abstract class DriverAbstract extends ObjectAbstract implements DriverInterface, ErrorAwareInterface, ExtensionAwareInterface
{
    use ErrorAwareTrait, ExtensionAwareTrait;

    /**
     * Store meta data in a seperate file
     *
     * @var    bool
     * @access protected
     */
    protected $use_metafile = false;

    /**
     * {@inheritDoc}
     */
    public function exists(/*# string */ $path)/*# : bool */
    {
        $real = $this->realPath($path);
        return $this->realExists($real);
    }

    /**
     * {@inheritDoc}
     */
    public function getContent(/*# string */ $path, /*# bool */ $stream = false)
    {
        $real = $this->realPath($path);
        if ($this->isRealDir($real)) {
            return $this->readDir($real, rtrim($path, '/\\') . '/');
        } elseif ($stream) {
            return $this->openReadStream($real);
        } else {
            return $this->readFile($real);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getMeta(/*# string */ $path)/*# : array */
    {
        $real = $this->realPath($path);

        if ($this->isRealDir($real)) {
            return [];
        } elseif ($this->use_metafile) {
            $meta = $this->readFile($real . '.meta');
            return is_string($meta) ? unserialize($meta) : [];
        } else {
            return $this->getRealMeta($real);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function setContent(/*# string */ $path, $content)/*# : bool */
    {
        $real = $this->realPath($path);

        if ($this->ensurePath($real)) {
            if (is_resource($content)) {
                $res = $this->writeStream($real, $content);
            } else {
                $res = $this->writeFile($real, $content);
            }

            return $res ?: $this->setError(
                Message::get(Message::STR_WRITEFILE_FAIL, $path),
                Message::STR_WRITEFILE_FAIL
            );
        }
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function setMeta(/*# string */ $path, array $meta)/*# : bool */
    {
        $real = $this->realPath($path);

        if ($this->use_metafile) {
            $new = array_replace($this->getMeta($path), $meta);
            $res = $this->writeFile($real . '.meta', serialize($new));
        } else {
            $res = $this->setRealMeta($real, $meta);
        }

        return $res ?: $this->setError(
            Message::get(Message::STR_SETMETA_FAIL, $real),
            Message::STR_SETMETA_FAIL
        );
    }

    /**
     * {@inheritDoc}
     */
    public function isDir(/*# string */ $path)/*# : bool */
    {
        $real = $this->realPath($path);
        return $this->isRealDir($real);
    }

    /**
     * {@inheritDoc}
     */
    public function rename(/*# string */ $from, /*# string */ $to)/*# : bool */
    {
        $real_to = $this->realPath($to);

        if ($this->ensurePath($real_to)) {
            $real_from = $this->realPath($from);

            if ($this->isRealDir($real_from)) {
                $res = $this->renameDir($real_from, $real_to);
            } else {
                $res = $this->renameFile($real_from, $real_to);
            }

            return $res ?: $this->setError(
                Message::get(Message::STR_RENAME_FAIL, $real_from, $real_to),
                Message::STR_RENAME_FAIL
            );
        }
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function copy(/*# string */ $from, /*# string */ $to)/*# : bool */
    {
        $real_from = $this->realPath($from);
        $real_to = $this->realPath($to);

        if ($this->ensurePath($real_to)) {
            if ($this->isRealDir($real_from)) {
                $res = $this->copyDir($real_from, $real_to);
            } else {
                $res = $this->copyFile($real_from, $real_to);
            }

            return $res ?: $this->setError(
                Message::get(Message::STR_COPY_FAIL, $real_from, $real_to),
                Message::STR_COPY_FAIL
            );
        }
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function delete(/*# string */ $path)/*# : bool */
    {
        $real = $this->realPath($path);

        if ($this->isRealDir($real)) {
            return $this->deleteDir($real, $path === '');
        } else {
            if ($this->use_metafile) {
                $this->deleteFile($real . '.meta');
            }
            return $this->deleteFile($real);
        }
    }

    /**
     * Returns driver specific real path
     * @param  string $path
     * @return string
     * @access protected
     */
    abstract protected function realPath(/*# string */ $path)/*# : string */;

    /**
     * Exists of real path
     *
     * @param  string $realPath
     * @return bool
     * @access protected
     */
    abstract protected function realExists(/*# string */ $realPath)/*# : bool */;

    /**
     * Is path a directory
     *
     * @param  string $realPath
     * @return bool
     * @access protected
     */
    abstract protected function isRealDir(/*# string */ $realPath)/*# : bool */;

    /**
     * Read directory, returns an array of paths in this directory
     *
     * @param  string $realPath
     * @param  string $prefix prefix to prepend to the results
     * @return array
     * @access protected
     */
    abstract protected function readDir(
        /*# string */ $realPath,
        /*# string */ $prefix = ''
    )/*# : array */;

    /**
     * create directory
     *
     * @param  string $realPath
     * @access protected
     */
    abstract  protected function makeDirectory(
        /*# string */ $realPath
    )/*# : bool */;

    /**
     * Open read stream
     *
     * @param  string $realPath
     * @return resource|null
     * @access protected
     */
    abstract protected function openReadStream(/*# string */ $realPath);

    /**
     * Read file and returns all the content
     *
     * @param  string $realPath
     * @return string|null
     * @access protected
     */
    abstract protected function readFile(/*# string */ $realPath);

    /**
     * Get the meta data
     *
     * @param  string $realPath
     * @return array
     * @access protected
     */
    abstract protected function getRealMeta(/*# string */ $realPath)/*# : array */;

    /**
     * Make sure path directory exits.
     *
     * @param  string $realPath
     * @return bool
     * @access protected
     */
    abstract protected function ensurePath(/*# string */ $realPath)/*# : bool */;

    /**
     * Write to file from stream
     *
     * @param  string $realPath
     * @param  resource $resource
     * @return bool
     * @access protected
     */
    abstract protected function writeStream(
        /*# string */ $realPath,
        $resource
    )/*# : bool */;

    /**
     * Write to file
     *
     * @param  string $realPath
     * @param  string $content
     * @return bool
     * @access protected
     */
    abstract protected function writeFile(
        /*# string */ $realPath,
        /*# string */ $content
    )/*# : bool */;

    /**
     * Write meta data
     *
     * @param  string $realPath
     * @param  array $meta
     * @return bool
     * @access protected
     */
    abstract protected function setRealMeta(
        /*# string */ $realPath,
        array $meta
    )/*# : bool */;

    /**
     * Rename directory
     *
     * @param  string $from
     * @param  string $to
     * @return bool
     * @access protected
     */
    abstract protected function renameDir(
        /*# string */ $from,
        /*# string */ $to
    )/*# : bool */;

    /**
     * Rename file
     *
     * @param  string $from
     * @param  string $to
     * @return bool
     * @access protected
     */
    abstract protected function renameFile(
        /*# string */ $from,
        /*# string */ $to
    )/*# : bool */;

    /**
     * Copy directory
     *
     * @param  string $from
     * @param  string $to
     * @return bool
     * @access protected
     */
    abstract protected function copyDir(
        /*# string */ $from,
        /*# string */ $to
    )/*# : bool */;

    /**
     * Copy file
     *
     * @param  string $from
     * @param  string $to
     * @return bool
     * @access protected
     */
    abstract protected function copyFile(
        /*# string */ $from,
        /*# string */ $to
    )/*# : bool */;

    /**
     * Delete directory
     *
     * @param  string $realPath
     * @param  bool keep the upper most directory
     * @return bool
     * @access protected
     */
    abstract protected function deleteDir(
        /*# string */ $realPath,
        /*# bool */ $keep = false
    )/*# : bool */;

    /**
     * Delete the file
     *
     * @param  string $realPath
     * @return bool
     * @access protected
     */
    abstract protected function deleteFile(/*# string */ $realPath)/*# : bool */;
}
