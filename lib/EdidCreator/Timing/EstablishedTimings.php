<?php
/**
 * Contains EstablishedTimings class.
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

/**
 * Class EstablishedTimings
 *
 * @package EdidCreator\Timing
 */
class EstablishedTimings extends AbstractEdidAwareComponent
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
     * @param string|string[] $value
     *
     * @throws \InvalidArgumentException
     * @throws \DomainException
     * @return self
     */
    public function addTimings($value)
    {
        if (is_string($value)) {
            $value = (array)$value;
        } elseif (!is_array($value)) {
            $mess = '$value MUST be an array or string NOT ' . gettype($value);
            throw new \InvalidArgumentException($mess);
        }
        foreach ($value as $timing) {
            if (array_key_exists($timing, $this->timingsToOffset)) {
                $this->edid->setBitField('1', $this->timingsToOffset[$timing]);
            } else {
                $mess = 'Unknown timing ' . $timing;
                throw new \DomainException($mess);
            }
        }
        return $this;
    }
    /**
     * @return string
     */
    public function getEstablishedTimings()
    {
        return $this->edid->getBitField($this->offset, $this->fieldLength);
    }
    /**
     * @return string[]
     */
    public function getTimingNames()
    {
        return array_keys($this->timingsToOffset);
    }
    /**
     * @param string $value
     *
     * @throws \InvalidArgumentException
     * @throws \DomainException
     * @return bool
     */
    public function hasTiming($value)
    {
        if (!is_string($value)) {
            $mess = '$value MUST be a string NOT ' . gettype($value);
            throw new \InvalidArgumentException($mess);
        }
        if (!array_key_exists($value, $this->timingsToOffset)) {
            $mess = 'Unknown timing ' . $value;
            throw new \DomainException($mess);
        }
        return (bool)$this->edid->getBitField(
                                $this->timingsToOffset[$value],
                                    1
        );
    }
    /**
     * @param string|int $value
     *
     * @throws \InvalidArgumentException
     * @throws \LengthException
     * @throws \DomainException
     * @return self
     */
    public function setEstablishedTimings($value)
    {
        $value = $this->convertValueToBitString($value, $this->fieldLength);
        $this->edid->setBitField($value, $this->offset);
        return $this;
    }
    /**
     * @var int
     */
    private $fieldLength = 24;
    /**
     * @var int[]
     */
    private $offset = array(35, 0);
    /**
     * @var array
     */
    private $timingsToOffset = array(
        '720*400@70' => array(35, 7), '720*400@88' => array(35, 6),
        '640*480@60' => array(35, 5), '640*480@67' => array(35, 4),
        '640*480@72' => array(35, 3), '640*480@75' => array(35, 2),
        '800*600@56' => array(35, 1), '800*600@60' => array(35, 0),
        '800*600@72' => array(36, 7), '800*600@75' => array(36, 6),
        '832*624@75' => array(36, 5), '1024*768@87' => array(36, 4),
        '1024*768@60' => array(36, 3), '1024*768@72' => array(36, 2),
        '1024*768@75' => array(36, 1), '1280*1024@75' => array(36, 0),
        '1152*870@75' => array(37, 7), 'custom6' => array(37, 6),
        'custom5' => array(37, 5), 'custom4' => array(37, 4),
        'custom3' => array(37, 3), 'custom2' => array(37, 2),
        'custom1' => array(37, 1), 'custom0' => array(37, 0)
    );
}
