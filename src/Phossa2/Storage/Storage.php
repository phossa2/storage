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
use Phossa2\Storage\Traits\PathAwareTrait;
use Phossa2\Shared\Error\ErrorAwareInterface;
use Phossa2\Storage\Interfaces\StorageInterface;
use Phossa2\Storage\Interfaces\MountableInterface;
use Phossa2\Storage\Interfaces\PathAwareInterface;

/**
 * Storage
 *
 * @package Phossa2\Storage
 * @author  Hong Zhang <phossa@126.com>
 * @version 2.0.0
 * @since   2.0.0 added
 */
class Storage extends ObjectAbstract implements StorageInterface, MountableInterface, PathAwareInterface, ErrorAwareInterface
{
    use PathAwareTrait;

    /**
     * {@inheritDoc}
     */
    public function has(/*# string */ $path)/*# : bool */
    {
        if ($this->path($path)->exists()) {
            return true;
        }

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
        $res = null;
        if ($this->has($path)) {
            $p = $this->path($path);
            if ($this->isFilesystemReadable($p->getFilesystem(), $path)) {
                $res = $p->getContent($stream);
                $this->copyError($p);
            }
        }
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
        $p = $this->path($path);
        if ($this->isFilesystemWritable($p->getFilesystem(), $path)) {
            $res = $p->setContent($content)->setMeta($meta)->write();
            $this->copyError($p);
            return $res;
        }
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function meta(/*# string */ $path)/*# : array */
    {
        if ($this->has($path)) {
            $p = $this->path($path);
            return $p->getMeta();
        }
        return [];
    }

    /**
     * {@inheritDoc}
     */
    public function copy(
        /*# string */ $from,
        /*# string */ $to,
        /*# bool */ $stream = false
    )/*# : bool */ {
        // same filesystem copy
        if ($this->isSameFilesystem($from, $to)) {
            return $this->sameFilesystemAction($from, $to, 'copy', $stream);
        }

        // read $from
        $content = $this->get($from, $stream);
        if ($this->hasError()) {
            return false;
        }

        // write $to
        $res = $this->put($to, $content);
        if (is_resource($content)) {
            fclose($content);
        }
        return $res;
    }

    /**
     * {@inheritDoc}
     */
    public function move(
        /*# string */ $from,
        /*# string */ $to,
        /*# bool */ $stream = false
    )/*# : bool */ {
        // same filesystem move
        if ($this->isSameFilesystem($from, $to)) {
            return $this->sameFilesystemAction($from, $to, 'rename', $stream);
        }

        // diff filesystem move
        if ($this->copy($from, $to, $stream)) {
            return $this->delete($from);
        }
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function delete(/*# string */ $path)/*# : bool */
    {
        if (!$this->has($path)) {
            return false;
        }

        $p = $this->path($path);
        if (!$this->isFilesystemDeletable($p->getFilesystem(), $path)) {
            return false;
        }

        $res = $p->delete();
        $this->copyError($p);

        return $res;
    }

    /**
     * Copy or rename
     *
     * @param  string $from
     * @param  string $to
     * @param  string $action 'copy' or 'rename'
     * @param  bool $stream using stream
     * @return bool
     * @access protected
     */
    protected function sameFilesystemAction(
        /*# string */ $from,
        /*# string */ $to,
        /*# string */ $action = 'copy',
        /*# bool */ $stream = false
    )/*# : bool */ {
        // check to
        $t = $this->path($to);
        if (!$this->isFilesystemWritable($t->getFilesystem(), $to)) {
            return false;
        }

        // check from
        if (!$this->has($from)) {
            return false;
        }

        // copy/rename
        $f = $this->path($from);
        $res = $f->{$action}($t->getPath(), $stream);

        $this->copyError($f);
        return $res;
    }

    /**
     * Exists on same filesystem ?
     *
     * @param  string $path1
     * @param  string $path2
     * @return boolean
     * @access protected
     */
    protected function isSameFilesystem(
        /*# string */ $path1,
        /*# string */ $path2
    )/*# : bool */ {
        return $this->path($path1)->getFilesystem() === $this->path($path2)->getFilesystem();
    }
}
