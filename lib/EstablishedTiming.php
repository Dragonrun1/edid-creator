<?php
/**
 * Contains EstablishedTiming class.
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

class EstablishedTiming extends AbstractEdidObject
{
    /**
     * @var integer[]
     */
    private $establishedTimings;
    /**
     * @var string[]
     */
    private $knownTimings = array(
        '800x600@60',
        '800x600@56',
        '640x480@75',
        '640x480@72',
        '640x480@67',
        '640x480@60',
        '720x400@88',
        '720x400@70',
        '1280x1024@75',
        '1024x768@75',
        '1024x768@72',
        '1024x768@60',
        '1024x768@87i',
        '832x624@75',
        '800x600@75',
        '800x600@72',
        'Reserved0',
        'Reserved1',
        'Reserved2',
        'Reserved3',
        'Reserved4',
        'Reserved5',
        'Reserved6',
        '1152x870@75'
    );
    /**
     * @return integer[]
     */
    public function getEstablishedTimings()
    {
        return $this->establishedTimings;
    }
    /**
     * @param integer[]|null $value
     *
     * @return self
     * @throws \LengthException
     */
    public function setEstablishedTimings(array $value = null)
    {
        if (is_null($value)) {
            $this->establishedTimings = array();
            return $this;
        }
        if (count($value) != 3) {
            $mess = $this->methodToPropertyWords(__FUNCTION__)
                . ' must have a length equal to 3';
            throw new \LengthException($mess);
        }
        $et = array();
        foreach ($value as $v) {
            $et[] = $v & 0xFF;
        }
        $this->establishedTimings = $et;
        return $this;
    }
    public function addEstablishedTiming($value)
    {
        if (!is_string($value)) {
            $mess = $this->methodToPropertyWords(__FUNCTION__)
                . ' requires string value';
            throw new \InvalidArgumentException($mess);
        }
        $find = array_search($value, $this->knownTimings);
        if (false === $find) {
            $mess = 'Unknown timing ' . $value;
            throw new \DomainException($mess);
        }
        $byte = $find >> 3;
        $bit = $find & 0x07;
        $et = $this->getEstablishedTimings();
        $et[$byte] |= (1 << $bit);
        return $this->setEstablishedTimings($et);
    }
    public function deleteEstablishedTiming($value)
    {
        if (!is_string($value)) {
            $mess = $this->methodToPropertyWords(__FUNCTION__)
                . ' requires string value';
            throw new \InvalidArgumentException($mess);
        }
        $find = array_search($value, $this->knownTimings);
        if (false === $find) {
            $mess = 'Unknown timing ' . $value;
            throw new \DomainException($mess);
        }
        $byte = $find >> 3;
        $bit = $find & 0x07;
        $et = $this->getEstablishedTimings();
        $et[$byte] &= ~(1 << $bit);
        return $this->setEstablishedTimings($et);
    }
    public function hasEstablishedTiming($value)
    {
        if (!is_string($value)) {
            $mess = $this->methodToPropertyWords(__FUNCTION__)
                . ' requires string value';
            throw new \InvalidArgumentException($mess);
        }
        $find = array_search($value, $this->knownTimings);
        if (false === $find) {
            $mess = 'Unknown timing ' . $value;
            throw new \DomainException($mess);
        }
        $byte = $find >> 3;
        $bit = $find & 0x07;
        $et = $this->getEstablishedTimings();
        return (bool)$et[$byte] & (1 << $bit);
    }
}
