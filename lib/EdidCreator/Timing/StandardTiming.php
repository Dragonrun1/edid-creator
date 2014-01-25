<?php
/**
 * Contains StandardTiming class.
 *
 * PHP version 5.3
 *
 * LICENSE:
 * This file is part of edid-creater
 * Copyright (C) 2014 Michael Cummings
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
 * @copyright 2014 Michael Cummings
 * @license   http://www.gnu.org/copyleft/lesser.html GNU LGPL
 * @author    Michael Cummings <mgcummings@yahoo.com>
 */
namespace EdidCreator\Timing;

use EdidCreator\AbstractEdidAwareComponent;
use EdidCreator\EdidBitFieldInterface;

/**
 * Class StandardTiming
 *
 * @package EdidCreator\Timing
 */
class StandardTiming extends AbstractEdidAwareComponent
{
    /**
     * @param int                        $position
     * @param EdidBitFieldInterface|null $edid
     */
    public function __construct(
        $position = 0,
        EdidBitFieldInterface $edid = null
    ) {
        $this->setByteOffset($position);
        $this->setEdid($edid);
    }
    /**
     * @param string|int $value
     *
     * @throws \InvalidArgumentException
     * @throws \LengthException
     * @throws \DomainException
     * @return self
     */
    public function __invoke($value = '0X0101')
    {
        $method = 'set' . basename(__CLASS__);
        return $this->$method($value);
    }
    /**
     * @return string
     */
    public function __toString()
    {
        $method = 'get' . basename(__CLASS__);
        return $this->$method();
    }
    /**
     * @return string
     */
    public function getActivePixels()
    {
        $fieldLength = 8;
        $offset = array($this->byteOffset, 0);
        return $this->edid->getBitField($offset, $fieldLength);
    }
    /**
     * @return string
     */
    public function getAspectRatio()
    {
        $fieldLength = 2;
        $offset = array($this->byteOffset + 1, 6);
        return $this->edid->getBitField($offset, $fieldLength);
    }
    /**
     * @return int
     */
    public function getByteOffset()
    {
        return ($this->byteOffset - $this->offsetBase) >> 1;
    }
    /**
     * @return string
     */
    public function getRefreshRate()
    {
        $fieldLength = 6;
        $offset = array($this->byteOffset + 1, 0);
        return $this->edid->getBitField($offset, $fieldLength);
    }
    /**
     * @return string
     */
    public function getStandardTiming()
    {
        $offset = array($this->byteOffset, 0);
        return $this->edid->getBitField($offset, $this->fieldLength);
    }
    /**
     * @param string|int $value
     *
     * @return self
     */
    public function setActivePixels($value)
    {
        $fieldLength = 8;
        $offset = array($this->byteOffset, 0);
        $value = $this->convertValueToBitString($value, $fieldLength);
        $this->edid->setBitField($value, $offset);
        return $this;
    }
    /**
     * @param int $value
     *
     * @throws \InvalidArgumentException
     * @return self
     */
    public function setByteOffset($value = 0)
    {
        if (!is_int($value)) {
            $mess = '$value MUST be an integer but received ' . gettype($value);
            throw new \InvalidArgumentException($mess);
        }
        $this->byteOffset = ((abs($value) & 7) << 1) + $this->offsetBase;
        return $this;
    }
    /**
     * @param string|int $value
     *
     * @return self
     */
    public function setRefreshRate($value)
    {
        $fieldLength = 6;
        $offset = array($this->byteOffset + 1, 0);
        $value = $this->convertValueToBitString($value, $fieldLength);
        $this->edid->setBitField($value, $offset);
        return $this;
    }
    /**
     * @param string|int $value
     *
     * @throws \InvalidArgumentException
     * @throws \LengthException
     * @throws \DomainException
     * @return self
     */
    public function setStandardTiming($value = '0X0101')
    {
        $offset = array($this->byteOffset, 0);
        $value = $this->convertValueToBitString($value, $this->fieldLength);
        $this->edid->setBitField($value, $offset);
        return $this;
    }
    /**
     * @var int
     */
    private $byteOffset;
    /**
     * @var int
     */
    private $fieldLength = 16;
    /**
     * @var int
     */
    private $offsetBase = 38;
}
