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
use Phossa2\Shared\Error\ErrorAwareTrait;
use Phossa2\Storage\Traits\PathAwareTrait;
use Phossa2\Shared\Error\ErrorAwareInterface;
use Phossa2\Storage\Interfaces\StorageInterface;
use Phossa2\Shared\Extension\ExtensionAwareTrait;
use Phossa2\Shared\Extension\ExtensionAwareInterface;

/**
 * Storage
 *
 * @package Phossa2\Storage
 * @author  Hong Zhang <phossa@126.com>
 * @see     ObjectAbstract
 * @see     StorageInterface
 * @see     ErrorAwareInterface
 * @see     ExtensionAwareInterface
 * @version 2.0.0
 * @since   2.0.0 added
 */
class Storage extends ObjectAbstract implements StorageInterface, ErrorAwareInterface, ExtensionAwareInterface
{
    use PathAwareTrait, ErrorAwareTrait, ExtensionAwareTrait;

    /**
     * {@inheritDoc}
     */
    public function has(/*# string */ $path)/*# : bool */
    {
        // found
        if ($this->path($path)->exists()) {
            return true;
        }

        // not found
        return $this->setError(
            Message::get(Message::MSG_PATH_NOTFOUND, $path),
            Message::MSG_PATH_NOTFOUND
        );
    }

    /**
     * {@inheritDoc}
     */
    public function get(/*# string */ $path, /*# bool */ $stream = false)
    {
        $obj = $this->path($path);
        $res = $obj->getContent($stream);

        // append mount point if result is array
        if (is_array($res)) {
            return $this->prependMountPoint(
                $res, $this->getMountPoint($obj->getFullPath())
            );
        }

        $this->copyError($obj);

        return $res;
    }

    /**
     * {@inheritDoc}
     */
    public function put(
        /*# string */ $path,
        $content,
        array $meta = []
    )/*# : bool */ {
        $obj = $this->path($path);

        // write content
        if (null !== $content && !$obj->setContent($content)) {
            $this->copyError($obj);
            return false;
        }

        // set meta if any
        $res = $obj->setMeta($meta);
        $this->copyError($obj);
        return $res;
    }

    /**
     * {@inheritDoc}
     */
    public function meta(/*# string */ $path)/*# : array */
    {
        $obj = $this->path($path);
        $res = $obj->getMeta();
        $this->copyError($obj);
        return $res;
    }

    /**
     * {@inheritDoc}
     */
    public function copy(
        /*# string */ $from,
        /*# string */ $to
    )/*# : bool */ {
        // same filesystem copy
        if ($this->isSameFilesystem($from, $to)) {
            return $this->sameFilesystemAction($from, $to, 'copy');
        }

        $content = $this->get($from);
        if (is_null($content)) {
            return false;
        } elseif (is_array($content)) {
            return $this->copyDir($content, $to);
        } else {
            return $this->put($to, $content);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function move(
        /*# string */ $from,
        /*# string */ $to
    )/*# : bool */ {
        // same filesystem move
        if ($this->isSameFilesystem($from, $to)) {
            return $this->sameFilesystemAction($from, $to, 'rename');
        }

        // diff filesystem move
        if ($this->copy($from, $to)) {
            return $this->delete($from);
        }
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function delete(/*# string */ $path)/*# : bool */
    {
        $obj = $this->path($path);
        $res = $obj->delete();
        $this->copyError($obj);
        return $res;
    }

    /**
     * Copy or rename
     *
     * @param  string $from
     * @param  string $to
     * @param  string $action 'copy' or 'rename'
     * @return bool
     * @access protected
     */
    protected function sameFilesystemAction(
        /*# string */ $from,
        /*# string */ $to,
        /*# string */ $action = 'copy'
    )/*# : bool */ {
        $obj = $this->path($from);
        $res = $obj->{$action}($this->path($to)->getPath());
        $this->copyError($obj);
        return $res;
    }

    /**
     * Exists on same filesystem ?
     *
     * @param  string $path1
     * @param  string $path2
     * @return bool
     * @access protected
     */
    protected function isSameFilesystem(
        /*# string */ $path1,
        /*# string */ $path2
    )/*# : bool */ {
        return $this->path($path1)->getFilesystem() === $this->path($path2)->getFilesystem();
    }

    /**
     * Copy an array of paths to destination
     *
     * @param  array $paths
     * @param  string $destination
     * @access protected
     */
    protected function copyDir(array $paths, /*# string */ $destination)
    {
        foreach ($paths as $path) {
            $base = basename($path);
            $dest = $this->mergePath($destination, $base);
            if (!$this->copy($path, $dest)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Prepend mount point prefix to the array of paths
     *
     * @param  array $paths
     * @param  string $mountPoint
     * @return array
     * @access protected
     */
    protected function prependMountPoint(
        array $paths,
        /*# string */ $mountPoint
    )/*# : array */ {
        $res = [];
        foreach ($paths as $p) {
            $res[] = $this->mergePath($mountPoint, $p);
        }
        return $res;
    }
}
