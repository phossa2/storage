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

use Phossa2\Storage\Path;
use Phossa2\Storage\Interfaces\PathAwareInterface;

/**
 * PathAwareTrait
 *
 * Implementation of PathAwareInterface
 *
 * @package Phossa2\Storage
 * @author  Hong Zhang <phossa@126.com>
 * @see     PathAwareInterface
 * @version 2.0.0
 * @since   2.0.0 added
 */
trait PathAwareTrait
{
    use MountableTrait;

    /**
     * cache of Path objects
     *
     * @var    Path[]
     * @access protected
     */
    protected $path_cache = [];

    /**
     * {@inheritDoc}
     */
    public function path(/*# string */ $path)/*# : Path */
    {
        // unique key
        $key = $this->getCacheKey($path);

        // try cache
        if ($this->hasPathCache($key)) {
            return $this->getFromCache($key);
        }

        // new Path object
        $obj = $this->newPath($this->normalize($path));

        // save to cache
        $this->saveToCache($key, $obj);

        return $obj;
    }

    /**
     * Normalize the path
     *
     * Replaces '.', '..', prepends '/', keeps trailing '/'
     *
     * @param  string $path
     * @return string
     * @access protected
     */
    protected function normalize(/*# string */ $path)/*# : string */
    {
        $pattern = ['~/{2,}~', '~/(\./)+~', '~([^/\.]+/(?R)*\.{2,}/)~', '~\.\./~'];
        $replace = ['/', '/', '', ''];
        return '/' . ltrim(preg_replace($pattern, $replace, $path), '/');
    }

    /**
     * Generate Path object. Override this method if you want to
     *
     * @param  string $path
     * @return Path
     * @access protected
     */
    protected function newPath(/*# string */ $path)/*# : Path */
    {
        list($mnt, $remain) = $this->splitPath($path);
        return new Path($path, $remain, $this->getFilesystemAt($mnt));
    }

    /**
     * Split into mount point and the remain
     *
     * @param  string $path
     * @return array [ mountpoint, remain ]
     * @access protected
     */
    protected function splitPath(/*# string */ $path)/*# : array */
    {
        // mount point
        $pref = $this->getMountPoint($path);

        // remains without leading '/'
        $remain = ltrim(substr($path, strlen($pref)), '/');

        return [$pref, $remain];
    }

    /**
     * Merge path
     *
     * @param  string $prefix
     * @param  string $suffix
     * @return string
     * @access protected
     */
    protected function mergePath(
        /*# string */ $prefix,
        /*# string */ $suffix
    )/*# : string */ {
        return rtrim($prefix, '/') . '/' . ltrim($suffix, '/');
    }

    /**
     * Is this path in cache ?
     *
     * @param  string $key
     * @return bool
     * @access protected
     */
    protected function hasPathCache(/*# string */ $key)/*# : bool */
    {
        return isset($this->path_cache[$key[0]][$key]);
    }

    /**
     * Get Path object from local cache
     *
     * @param  string $key
     * @return Path
     * @access protected
     */
    protected function getFromCache(/*# string */ $key)
    {
        return $this->path_cache[$key[0]][$key];
    }

    /**
     * Save Path object to local cache
     *
     * @param  string $key
     * @param  object $obj
     * @access protected
     */
    protected function saveToCache(/*# string */ $key, $obj)
    {
        // clear stale cache
        if (isset($this->path_cache[$key[0]]) &&
            sizeof($this->path_cache[$key[0]]) > 1
        ) {
            $this->path_cache[$key[0]] = [];
        }

        $this->path_cache[$key[0]][$key] = $obj;
    }

    /**
     * Generate an unique key for the path
     *
     * @param  string $path
     * @return string
     * @access protected
     */
    protected function getCacheKey(/*# string */ $path)/*# : string */
    {
        return md5($path);
    }
}
