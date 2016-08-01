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

use Phossa2\Storage\Interfaces\PathInterface;
use Phossa2\Storage\Interfaces\PathAwareInterface;
use Phossa2\Storage\Path;

/**
 * PathAwareTrait
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
     * Temp cache of Path objects
     *
     * @var    array
     * @access protected
     */
    protected $path_cache = [];

    /**
     * {@inheritDoc}
     */
    public function path(/*# string */ $path)/*# : PathInterface */
    {
        // try cache
        $obj = $this->getFromCache($path);
        if (is_object($obj)) {
            return $obj;
        }

        // normalize path
        $norm = $this->normalize($path);

        // split path
        list($mnt, $remain) = $this->splitPath($norm);

        $obj = new Path($remain, $this->getFilesystemAt($mnt));

        // save to cache
        $this->saveToCache($path, $obj);

        return $obj;
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

        // remain
        $remain = ltrim(substr($path, strlen($pref)), '/');

        return [$pref, $remain];
    }

    /**
     * Get Path object from local cache
     *
     * @param  string $path
     * @return false|PathInterface
     * @access protected
     */
    protected function getFromCache(/*# string */ $path)
    {
        $key = $this->getCacheKey($path);
        if (isset($this->path_cache[$key[0]][$key])) {
            return $this->path_cache[$key[0]][$key];
        }
        return false;
    }

    /**
     * Save Path object to local cache
     *
     * @param  string $path
     * @param  PathInterface $obj
     * @access protected
     */
    protected function saveToCache(/*# string */ $path, PathInterface $obj)
    {
        $key = $this->getCacheKey($path);

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
