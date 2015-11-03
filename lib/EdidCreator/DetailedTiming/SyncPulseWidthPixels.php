<?php
/**
 * Contains SyncPulseWidthPixels class.
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
 * Class SyncPulseWidthPixels
 *
 * @package EdidCreator\DetailedTiming
 */
class SyncPulseWidthPixels extends AbstractDetailedTimingComponent
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
    public function setSyncPulseWidthPixels($value)
    {
        $fieldLength = 10;
        $value = $this->convertValueToBitString($value, $fieldLength);
        $msb = substr($value, 0, 2);
        $offset = array($this->byteOffset + 11, 4);
        $this->edid->setBitField($msb, $offset);
        $value = substr($value, -8);
        $offset = array($this->byteOffset + 9, 0);
        $this->edid->setBitField($value, $offset);
        return $this;
    }
    /**
     * @return string
     */
    public function getSyncPulseWidthPixels()
    {
        $offset = array($this->byteOffset + 11, 4);
        $value = $this->edid->getBitField($offset, 2);
        $offset = array($this->byteOffset + 9, 0);
        $value .= $this->edid->getBitField($offset);
        return $value;
    }
}
