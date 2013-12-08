<?php
/**
 * Contains MonitorRangeLimitsDescriptor class.
 *
 * PHP version 5.3
 *
 * LICENSE:
 * This file is part of Edid Creator which can be used to create a version 1.3 Extended Display Identification Data
 * binary file.
 * Copyright (C) 2013  Michael Cummings
 *
 * This program is free software: you can redistribute it and/or modify it under the terms of the GNU Lesser General
 * Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option)
 * any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
 * warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Lesser General Public License along with this program. If not, see
 * <http://www.gnu.org/licenses/>.
 *
 * You should be able to find a copy of this license in the LICENSE.md file. A copy of the GNU GPL should also be
 * available in the GNU-GPL.md file.
 *
 * @author    Michael Cummings <mgcummings@yahoo.com>
 * @copyright 2013 Michael Cummings
 * @license   http://www.gnu.org/copyleft/lesser.html GNU LGPL
 */
namespace EdidCreator;

class MonitorRangeLimitsDescriptor
{
    /**
     * @var integer[]
     */
    private $extendedTiming = array(
        0x00,
        0x0A,
        0x20,
        0x20,
        0x20,
        0x20,
        0x20,
        0x20
    );
    /**
     * @var integer[]
     */
    private $header = array(0x00, 0x00, 0xFD, 0x00);
    /**
     * @var integer
     */
    private $maximumHorizontalRate;
    /**
     * @var integer
     */
    private $maximumPixelClockRate;
    /**
     * @var integer
     */
    private $maximumVerticalRate;
    /**
     * @var integer
     */
    private $minimumHorizontalRate;
    /**
     * @var integer
     */
    private $minimumVerticalRate;
    /**
     * @return integer[integer]
     */
    public function getAllAsIntegerArray()
    {
        return array_merge(
            $this->getHeader(),
            (array)$this->getMinimumVerticalRate(),
            (array)$this->getMaximumVerticalRate(),
            (array)$this->getMinimumHorizontalRate(),
            (array)$this->getMaximumHorizontalRate(),
            (array)$this->getMaximumPixelClockRate(),
            $this->getExtendedTiming()
        );
    }
    /**
     * @return integer[]
     */
    public function getExtendedTiming()
    {
        return $this->extendedTiming;
    }
    /**
     * @return integer[]
     */
    public function getHeader()
    {
        return $this->header;
    }
    /**
     * @return int
     */
    public function getMaximumHorizontalRate()
    {
        return $this->maximumHorizontalRate;
    }
    /**
     * @return int
     */
    public function getMaximumPixelClockRate()
    {
        return $this->maximumPixelClockRate;
    }
    /**
     * @return int
     */
    public function getMaximumVerticalRate()
    {
        return $this->maximumVerticalRate;
    }
    /**
     * @return int
     */
    public function getMinimumHorizontalRate()
    {
        return $this->minimumHorizontalRate;
    }
    /**
     * @return int
     */
    public function getMinimumVerticalRate()
    {
        return $this->minimumVerticalRate;
    }
    /**
     * @param int $value
     *
     * @return self
     */
    public function setMaximumHorizontalRate($value)
    {
        $value = abs((int)$value % 256);
        $this->maximumHorizontalRate = $value;
        return $this;
    }
    /**
     * @param int $value
     *
     * @return self
     */
    public function setMaximumPixelClockRate($value)
    {
        $value = abs((int)$value % 256);
        $this->maximumPixelClockRate = $value;
        return $this;
    }
    /**
     * @param int $value
     *
     * @return self
     */
    public function setMaximumVerticalRate($value)
    {
        $value = abs((int)$value % 256);
        $this->maximumVerticalRate = $value;
        return $this;
    }
    /**
     * @param int $value
     *
     * @return self
     */
    public function setMinimumHorizontalRate($value)
    {
        $value = abs((int)$value % 256);
        $this->minimumHorizontalRate = $value;
        return $this;
    }
    /**
     * @param int $value
     *
     * @return self
     */
    public function setMinimumVerticalRate($value)
    {
        $value = abs((int)$value % 256);
        $this->minimumVerticalRate = $value;
        return $this;
    }
}
