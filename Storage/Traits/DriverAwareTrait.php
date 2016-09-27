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

use Phossa2\Storage\Interfaces\DriverInterface;
use Phossa2\Storage\Interfaces\DriverAwareInterface;

/**
 * DriverAwareTrait
 *
 * Implementation of DriverAwareInterface
 *
 * @package Phossa2\Storage
 * @author  Hong Zhang <phossa@126.com>
 * @see     DriverAwareInterface
 * @version 2.0.0
 * @since   2.0.0 added
 */
trait DriverAwareTrait
{
    /**
     * @var    DriverInterface
     * @access protected
     */
    protected $driver;

    /**
     * {@inheritDoc}
     */
    public function setDriver(DriverInterface $driver)
    {
        $this->driver = $driver;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getDriver()/*# : DriverInterface */
    {
        return $this->driver;
    }
}
