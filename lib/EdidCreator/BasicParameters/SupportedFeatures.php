<?php
/**
 * Contains SupportedFeatures class.
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
namespace EdidCreator\BasicParameters;

use EdidCreator\AbstractEdidAwareComponent;

/**
 * Class SupportedFeatures
 *
 * @package EdidCreator\BasicParameters
 */
class SupportedFeatures extends AbstractEdidAwareComponent
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
    public function getDisplayType()
    {
        $fieldLength = 2;
        $offset = array(24, 3);
        return $this->edid->getBitField($offset, $fieldLength);
    }
    /**
     * @return string
     */
    public function getDpms()
    {
        $fieldLength = 3;
        $offset = array(24, 5);
        return $this->edid->getBitField($offset, $fieldLength);
    }
    /**
     * @return string
     */
    public function getSupportedFeatures()
    {
        return $this->edid->getBitField($this->offset);
    }
    /**
     * @return bool
     */
    public function isDefaultGtfSupported()
    {
        $fieldLength = 1;
        $offset = array(24, 0);
        return (bool)$this->edid->getBitField($offset, $fieldLength);
    }
    /**
     * @return bool
     */
    public function isPreferredTimingMode()
    {
        $fieldLength = 1;
        $offset = array(24, 1);
        return (bool)$this->edid->getBitField($offset, $fieldLength);
    }
    /**
     * @return bool
     */
    public function isStandardColorSpace()
    {
        $fieldLength = 1;
        $offset = array(24, 2);
        return (bool)$this->edid->getBitField($offset, $fieldLength);
    }
    /**
     * @param bool $value
     *
     * @throws \InvalidArgumentException
     * @return self
     */
    public function setDefaultGtfSupported($value)
    {
        if (is_bool($value)) {
            $value = $value ? '1' : '0';
        } else {
            $mess = '$value MUST be a boolean but received '
                . gettype($value);
            throw new \InvalidArgumentException($mess);
        }
        $offset = array(24, 0);
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
    public function setDisplayType($value)
    {
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
        $offset = array(24, 3);
        $this->edid->setBitField($value, $offset);
        return $this;
    }
    /**
     * @param string|int $value
     *
     * @throws \InvalidArgumentException
     * @throws \LengthException
     * @throws \DomainException
     *
     * @return self
     */
    public function setDpms($value)
    {
        $fieldLength = 3;
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
        $offset = array(24, 5);
        $this->edid->setBitField($value, $offset);
        return $this;
    }
    /**
     * @param bool $value
     *
     * @throws \InvalidArgumentException
     * @return self
     */
    public function setPreferredTimingMode($value)
    {
        if (is_bool($value)) {
            $value = $value ? '1' : '0';
        } else {
            $mess = '$value MUST be a boolean but received '
                . gettype($value);
            throw new \InvalidArgumentException($mess);
        }
        $offset = array(24, 1);
        $this->edid->setBitField($value, $offset);
        return $this;
    }
    /**
     * @param bool $value
     *
     * @throws \InvalidArgumentException
     * @return self
     */
    public function setStandardColorSpace($value)
    {
        if (is_bool($value)) {
            $value = $value ? '1' : '0';
        } else {
            $mess = '$value MUST be a boolean but received '
                . gettype($value);
            throw new \InvalidArgumentException($mess);
        }
        $offset = array(24, 2);
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
    public function setSupportedFeatures($value)
    {
        $value = $this->convertValueToBitString($value);
        $this->edid->setBitField($value, $this->offset);
        return $this;
    }
    /**
     * @var int[]
     */
    private $offset = array(24, 0);
}
