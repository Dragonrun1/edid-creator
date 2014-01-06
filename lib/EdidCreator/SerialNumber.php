<?php
/**
 * Contains Header class.
 *
 * PHP version 5.3
 *
 * LICENSE:
 * This file is part of Edid Creator which can be used to create a version 1.3 Extended Display Identification Data
 * binary file.
 * Copyright (C) 2014  Michael Cummings
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
 * @copyright 2014 Michael Cummings
 * @license   http://www.gnu.org/copyleft/lesser.html GNU LGPL
 */
namespace EdidCreator;

class SerialNumber implements EdidAwareInterface
{
    /**
     * @param EdidBitFieldInterface $edid
     */
    public function __construct(EdidBitFieldInterface $edid)
    {
        $this->setEdid($edid);
    }
    /**
     * @param EdidBitFieldInterface $edid
     *
     * @return self
     */
    public function setEdid(EdidBitFieldInterface $edid)
    {
        $this->edid = $edid;
        return $this;
    }
    /**
     * @param string $value
     *
     * @throws \RangeException
     * @throws \InvalidArgumentException
     * @throws \LengthException
     * @throws \DomainException
     * @return self
     */
    public function setSerialNumber($value)
    {
        if (!is_string($value)) {
            $mess = '$value MUST be a string but received ' . gettype($value);
            throw new \InvalidArgumentException($mess);
        }
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
        } else {
            $mess = '$value MUST be prefixed hexadecimal or binary string';
            throw new \DomainException($mess);
        }
        if ($vCount != $this->fieldLength) {
            $mess = 'Binary integer MUST be '
                . $this->fieldLength
                . ' bits long but is instead '
                . $vCount;
            throw new \LengthException($mess);
        }
        $this->edid->setBitField($value, array(12, 0));
        return $this;
    }
    /**
     * @var EdidBitFieldInterface
     */
    private $edid;
    private $fieldLength = 32;
    /**
     * @param string $value
     *
     * @return string
     */
    private function hexadecimalStringToBinaryString($value)
    {
        $value = str_split($value);
        $bits = '';
        foreach ($value as $v) {
            $bits .= str_pad(decbin(hexdec($v)), 4, '0', STR_PAD_LEFT);
        }
        return $bits;
    }
}
