<?php
/**
 * Contains Features class.
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
namespace EdidCreator\DetailedTiming;

/**
 * Class Features
 *
 * @package EdidCreator\DetailedTiming
 */
class Features extends AbstractDetailedTimingComponent
{
    /**
     * @param string|int $value
     *
     * @throws \InvalidArgumentException
     * @throws \LengthException
     * @throws \DomainException
     * @return self
     */
    public function __invoke($value)
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
     * @param string|int $value
     *
     * @throws \InvalidArgumentException
     * @throws \LengthException
     * @throws \DomainException
     * @return self
     */
    public function setFeatures($value)
    {
        $value = $this->convertValueToBitString($value);
        $offset = array($this->byteOffset + 17, 0);
        $this->edid->setBitField($value, $offset);
        return $this;
    }
    /**
     * @return string
     */
    public function getFeatures()
    {
        $offset = array($this->byteOffset + 17, 0);
        return $this->edid->getBitField($offset);
    }
    /**
     * @param bool $value
     *
     * @throws \InvalidArgumentException
     * @return self
     */
    public function setInterlaced($value)
    {
        if (is_bool($value)) {
            $value = $value ? '1' : '0';
        } else {
            $mess = '$value MUST be a boolean but received ' . gettype($value);
            throw new \InvalidArgumentException($mess);
        }
        $offset = array($this->byteOffset + 17, 7);
        $this->edid->setBitField($value, $offset);
        return $this;
    }
    /**
     * @return bool
     */
    public function isInterlaced()
    {
        $fieldLength = 1;
        $offset = array($this->byteOffset + 17, 7);
        return (bool)$this->edid->getBitField($offset, $fieldLength);
    }
    /**
     * @param string|int $value
     *
     * @return self
     */
    public function setStereoMode($value)
    {
        $fieldLength = 2;
        $offset = array($this->byteOffset + 17, 5);
        $value = $this->convertValueToBitString($value, $fieldLength);
        $this->edid->setBitField($value, $offset);
        return $this;
    }
    /**
     * @return string
     */
    public function getStereoMode()
    {
        $fieldLength = 2;
        $offset = array($this->byteOffset + 17, 5);
        return $this->edid->getBitField($offset, $fieldLength);
    }
    /**
     * @param string|int $value
     *
     * @return self
     */
    public function setSyncType($value)
    {
        $fieldLength = 2;
        $offset = array($this->byteOffset + 17, 3);
        $value = $this->convertValueToBitString($value, $fieldLength);
        $this->edid->setBitField($value, $offset);
        return $this;
    }
    /**
     * @return string
     */
    public function getSyncType()
    {
        $fieldLength = 2;
        $offset = array($this->byteOffset + 17, 3);
        return $this->edid->getBitField($offset, $fieldLength);
    }
    /**
     * @param bool $value
     *
     * @throws \InvalidArgumentException
     * @return self
     */
    public function setInterleaved($value)
    {
        if (is_bool($value)) {
            $value = $value ? '1' : '0';
        } else {
            $mess = '$value MUST be a boolean but received ' . gettype($value);
            throw new \InvalidArgumentException($mess);
        }
        $offset = array($this->byteOffset + 17, 0);
        $this->edid->setBitField($value, $offset);
        return $this;
    }
    /**
     * @return bool
     */
    public function isInterleaved()
    {
        $fieldLength = 1;
        $offset = array($this->byteOffset + 17, 0);
        return (bool)$this->edid->getBitField($offset, $fieldLength);
    }
    /**
     * @param bool $value
     *
     * @throws \InvalidArgumentException
     * @return self
     */
    public function setVerticalSync($value)
    {
        if (is_bool($value)) {
            $value = $value ? '1' : '0';
        } else {
            $mess = '$value MUST be a boolean but received ' . gettype($value);
            throw new \InvalidArgumentException($mess);
        }
        $offset = array($this->byteOffset + 17, 2);
        $this->edid->setBitField($value, $offset);
        return $this;
    }
    /**
     * @return bool
     */
    public function isVerticalSync()
    {
        $fieldLength = 1;
        $offset = array($this->byteOffset + 17, 2);
        return (bool)$this->edid->getBitField($offset, $fieldLength);
    }
    /**
     * @param bool $value
     *
     * @throws \InvalidArgumentException
     * @return self
     */
    public function setHorizontalSync($value)
    {
        if (is_bool($value)) {
            $value = $value ? '1' : '0';
        } else {
            $mess = '$value MUST be a boolean but received ' . gettype($value);
            throw new \InvalidArgumentException($mess);
        }
        $offset = array($this->byteOffset + 17, 1);
        $this->edid->setBitField($value, $offset);
        return $this;
    }
    /**
     * @return bool
     */
    public function isHorizontalSync()
    {
        $fieldLength = 1;
        $offset = array($this->byteOffset + 17, 1);
        return (bool)$this->edid->getBitField($offset, $fieldLength);
    }
}
