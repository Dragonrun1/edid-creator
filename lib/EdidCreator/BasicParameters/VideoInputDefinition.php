<?php
/**
 * Contains VideoInputDefinition class.
 *
 * PHP version 5.3
 *
 * LICENSE:
 * This file is part of edid-creater which can be used to create a version 1.3 Extended Display Identification Data
 * binary file.
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
namespace EdidCreator\BasicParameters;

use EdidCreator\AbstractEdidAwareComponent;

/**
 * Class VideoInputDefinition
 *
 * @package EdidCreator
 */
class VideoInputDefinition extends AbstractEdidAwareComponent
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
     * @return string
     */
    public function getSignalLevel()
    {
        $fieldLength = 1;
        $offset = array(20, 7);
        return $this->edid->getBitField($offset, $fieldLength);
    }
    /**
     * @throws \LogicException
     * @return string
     */
    public function getSignalingStandard()
    {
        if ($this->isDigitalSignalLevel()) {
            $mess = 'SignalingStandard is only used with analog signal level';
            throw new \LogicException($mess);
        }
        $fieldLength = 2;
        $offset = array(20, 5);
        return $this->edid->getBitField($offset, $fieldLength);
    }
    /**
     * @throws \LogicException
     * @return string
     */
    public function getSupportedSyncs()
    {
        if ($this->isDigitalSignalLevel()) {
            $mess = 'SignalingStandard is only used with analog signal level';
            throw new \LogicException($mess);
        }
        $fieldLength = 4;
        $offset = array(20, 1);
        return $this->edid->getBitField($offset, $fieldLength);
    }
    /**
     * @return string
     */
    public function getVideoInputDefinition()
    {
        return $this->edid->getBitField($this->offset);
    }
    /**
     * @throws \LogicException
     * @return bool
     */
    public function hasSetup()
    {
        if ($this->isDigitalSignalLevel()) {
            $mess = 'Setup is only used with analog signal level';
            throw new \LogicException($mess);
        }
        $fieldLength = 1;
        $offset = array(20, 4);
        return (bool)$this->edid->getBitField($offset, $fieldLength);
    }
    /**
     * @return bool
     */
    public function isAnalogSignalLevel()
    {
        return !(bool)$this->getSignalLevel();
    }
    /**
     * @throws \LogicException
     * @return bool
     */
    public function isDfp()
    {
        if ($this->isAnalogSignalLevel()) {
            $mess = 'DFP is only used with digital signal level';
            throw new \LogicException($mess);
        }
        $fieldLength = 1;
        $offset = array(20, 0);
        return (bool)$this->edid->getBitField($offset, $fieldLength);
    }
    /**
     * @return bool
     */
    public function isDigitalSignalLevel()
    {
        return (bool)$this->getSignalLevel();
    }
    /**
     * @param bool $value
     *
     * @throws \LogicException
     * @throws \InvalidArgumentException
     * @return self
     */
    public function setDfp($value)
    {
        if ($this->isAnalogSignalLevel()) {
            $mess = 'DFP can only be set if mode is digital';
            throw new \LogicException($mess);
        }
        if (is_bool($value)) {
            $value = $value ? '1' : '0';
        } else {
            $mess = '$value MUST be a boolean but received '
                . gettype($value);
            throw new \InvalidArgumentException($mess);
        }
        $this->edid->setBitField($value, $this->offset);
        return $this;
    }
    /**
     * @param bool $value
     *
     * @throws \LogicException
     * @return self
     */
    public function setSetup($value)
    {
        if ($this->isDigitalSignalLevel()) {
            $mess = 'Setup is only used with analog signal level';
            throw new \LogicException($mess);
        }
        if (is_bool($value)) {
            $value = $value ? '1' : '0';
        } else {
            $mess = '$value MUST be a boolean but received '
                . gettype($value);
            throw new \InvalidArgumentException($mess);
        }
        $offset = array(20, 4);
        $this->edid->setBitField($value, $offset);
        return $this;
    }
    /**
     * @param string $value
     *
     * @throws \InvalidArgumentException
     * @throws \DomainException
     * @return self
     */
    public function setSignalLevel($value)
    {
        if (is_string($value)) {
            $value = strtolower($value);
            if ($value == 'analog') {
                $value = '0';
            } elseif ($value = 'digital') {
                $value = '1';
            } else {
                $mess = 'Unknown signal level ' . $value;
                throw new \DomainException($mess);
            }
        } elseif (is_bool($value)) {
            $value = $value ? '1' : '0';
        } else {
            $mess = '$value MUST be a boolean or a string but received '
                . gettype($value);
            throw new \InvalidArgumentException($mess);
        }
        $offset = array(20, 7);
        $this->edid->setBitField($value, $offset);
        return $this;
    }
    /**
     * @param string|int $value
     *
     * @throws \LogicException
     * @throws \InvalidArgumentException
     * @throws \LengthException
     * @throws \DomainException
     * @return self
     */
    public function setSignalingStandard($value)
    {
        if ($this->isDigitalSignalLevel()) {
            $mess = 'SignalingStandard is only used with analog signal level';
            throw new \LogicException($mess);
        }
        $fieldLength = 2;
        if (is_int($value)) {
            $value =
                str_pad(decbin(abs($value)), $fieldLength, '0', STR_PAD_LEFT);
            $value = substr($value, -$fieldLength);
        } elseif (is_string($value)) {
            $value = strtoupper($value);
            $prefix = substr($value, 0, 2);
            $value = substr($value, 2);
            $vCount = strlen($value);
            if ($prefix == '0X') {
                if ($vCount != strspn($value, '0123456789ABCDEF')) {
                    $mess = '$value is NOT a hexadecimal string';
                    throw new \DomainException($mess);
                }
                $value =
                    $this->hexadecimalStringToBinaryString(
                         $value,
                             $fieldLength
                    );
            } elseif ($prefix == '0B') {
                if ($vCount != strspn($value, '01')) {
                    $mess = '$value is NOT a binary integer string';
                    throw new \DomainException($mess);
                }
            } else {
                $mess = '$value MUST be prefixed hexadecimal or binary string';
                throw new \DomainException($mess);
            }
        } else {
            $mess = '$value MUST be a string or an integer but received '
                . gettype($value);
            throw new \InvalidArgumentException($mess);
        }
        $vCount = strlen($value);
        if ($vCount != $fieldLength) {
            $mess = 'Binary integer MUST be '
                . $fieldLength
                . ' bits long but is instead '
                . $vCount;
            throw new \LengthException($mess);
        }
        $offset = array(20, 5);
        $this->edid->setBitField($value, $offset);
        return $this;
    }
    /**
     * @param string|int $value
     *
     * @throws \LogicException
     * @throws \InvalidArgumentException
     * @throws \LengthException
     * @throws \DomainException
     * @return self
     */
    public function setSupportedSyncs($value)
    {
        if ($this->isDigitalSignalLevel()) {
            $mess = 'SupportedSyncs is only used with analog signal level';
            throw new \LogicException($mess);
        }
        $fieldLength = 4;
        if (is_int($value)) {
            $value =
                str_pad(decbin(abs($value)), $fieldLength, '0', STR_PAD_LEFT);
            $value = substr($value, -$fieldLength);
        } elseif (is_string($value)) {
            $value = strtoupper($value);
            $prefix = substr($value, 0, 2);
            $value = substr($value, 2);
            $vCount = strlen($value);
            if ($prefix == '0X') {
                if ($vCount != strspn($value, '0123456789ABCDEF')) {
                    $mess = '$value is NOT a hexadecimal string';
                    throw new \DomainException($mess);
                }
                $value =
                    $this->hexadecimalStringToBinaryString(
                         $value,
                             $fieldLength
                    );
            } elseif ($prefix == '0B') {
                if ($vCount != strspn($value, '01')) {
                    $mess = '$value is NOT a binary integer string';
                    throw new \DomainException($mess);
                }
            } else {
                $mess = '$value MUST be prefixed hexadecimal or binary string';
                throw new \DomainException($mess);
            }
        } else {
            $mess = '$value MUST be a string or an integer but received '
                . gettype($value);
            throw new \InvalidArgumentException($mess);
        }
        $vCount = strlen($value);
        if ($vCount != $fieldLength) {
            $mess = 'Binary integer MUST be '
                . $fieldLength
                . ' bits long but is instead '
                . $vCount;
            throw new \LengthException($mess);
        }
        $offset = array(20, 1);
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
    public function setVideoInputDefinition($value)
    {
        $value = $this->convertValueToBitString($value);
        if ($value > '10000001') {
            $mess = 'Bits 1-6 are reserved';
            throw new \DomainException($mess);
        }
        $this->edid->setBitField($value, $this->offset);
        return $this;
    }
    /**
     * @var int[]
     */
    private $offset = array(20, 0);
}
