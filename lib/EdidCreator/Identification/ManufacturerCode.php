<?php
/**
 * Contains ManufacturerCode class.
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
namespace EdidCreator\Identification;

use EdidCreator\AbstractEdidAwareComponent;

/**
 * Class ManufacturerCode
 *
 * @package EdidCreator
 */
class ManufacturerCode extends AbstractEdidAwareComponent
{
    /**
     * @param string|int $value
     *
     * @throws \RangeException
     * @throws \InvalidArgumentException
     * @throws \LengthException
     * @throws \DomainException
     * @return self
     */
    public function setManufacturerCode($value)
    {
        $value = $this->convertValueToBitString($value, $this->fieldLength);
        $this->edid->setBitField($value, $this->offset);
        return $this;
    }
    /**
     * @param string|int $value
     * @param int        $fieldLength
     *
     * @throws \InvalidArgumentException
     * @throws \LengthException
     * @throws \DomainException
     * @return string
     */
    protected function convertValueToBitString($value, $fieldLength)
    {
        if (!is_int($fieldLength)) {
            $mess = '$fieldLength MUST be an integer but received '
                . gettype($value);
            throw new \InvalidArgumentException($mess);
        }
        if (is_int($value)) {
            $value =
                str_pad(decbin(abs($value)), $fieldLength, '0', STR_PAD_LEFT);
            $vCount = strlen($value);
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
                $value = $this->hexadecimalStringToBinaryString($value);
                $vCount = strlen($value);
            } elseif ($prefix == '0B') {
                if ($vCount != strspn($value, '01')) {
                    $mess = '$value is NOT a binary integer string';
                    throw new \DomainException($mess);
                }
            } elseif ($vCount == 3) {
                $value =
                    $this->charManufacturerCodeToBinaryIntegerString($value);
                $vCount = strlen($value);
            } else {
                $mess = '$value MUST be prefixed hexadecimal or binary '
                    . 'or 3 character alphabetic string';
                throw new \DomainException($mess);
            }
        } else {
            $mess = '$value MUST be a string or an integer but received '
                . gettype($value);
            throw new \InvalidArgumentException($mess);
        }
        if ($vCount != $fieldLength) {
            $mess = 'Binary integer MUST be '
                . $fieldLength
                . ' bits long but is instead '
                . $vCount;
            throw new \LengthException($mess);
        }
        return $value;
    }
    /**
     * @param string $value
     *
     * @throws \DomainException
     * @return string
     */
    private function charManufacturerCodeToBinaryIntegerString($value)
    {
        $ordA = 65;
        $ordZ = 90;
        $ordAMinusOne = $ordA - 1;
        $bitmap = 0;
        for ($i = 0;$i < 3;++$i) {
            $ord = ord($value[$i]);
            if ($ord < $ordA || $ord > $ordZ) {
                $mess =
                    'Manufacturer Ids can only be the letters A-Z or a-z';
                throw new \DomainException($mess);
            }
            $bitmap = ($bitmap << 5) | ($ord - $ordAMinusOne);
        }
        return decbin($bitmap);
    }
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
     * @var int
     */
    private $fieldLength = 8;
    /**
     * @var int[]
     */
    private $offset = array(8, 0);
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
    public function getManufacturerCode()
    {
        return $this->edid->getBitField($this->fieldLength, $this->offset);
    }
}
