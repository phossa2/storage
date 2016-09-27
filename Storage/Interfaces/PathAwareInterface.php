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

namespace Phossa2\Storage\Interfaces;

use Phossa2\Storage\Path;

/**
 * PathAwareInterface
 *
 * @package Phossa2\Storage
 * @author  Hong Zhang <phossa@126.com>
 * @version 2.0.0
 * @since   2.0.0 added
 */
interface PathAwareInterface
{
    /**
     * Returns a Path object
     *
     * @param  string $path
     * @return Path
     * @access public
     * @api
     */
    public function path(/*# string */ $path)/*# : Path */;
}
