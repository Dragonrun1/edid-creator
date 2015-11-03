<?php
/**
 * Contains BlankingPixels class.
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
 * Class BlankingPixels
 *
 * @package EdidCreator\DetailedTiming
 */
class BlankingPixels extends AbstractDetailedTimingComponent
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
    public function setBlankingPixels($value)
    {
        $fieldLength = 12;
        $value = $this->convertValueToBitString($value, $fieldLength);
        $msb = substr($value, 0, 4);
        $offset = array($this->byteOffset + 4, 0);
        $this->edid->setBitField($msb, $offset);
        $value = substr($value, -8);
        $offset = array($this->byteOffset + 3, 0);
        $this->edid->setBitField($value, $offset);
        return $this;
    }
    /**
     * @return string
     */
    public function getBlankingPixels()
    {
        $offset = array($this->byteOffset + 4, 0);
        $value = $this->edid->getBitField($offset, 4);
        $offset = array($this->byteOffset + 3, 0);
        $value .= $this->edid->getBitField($offset);
        return $value;
    }
}
