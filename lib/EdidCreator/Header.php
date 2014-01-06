<?php
/**
 * Contains Header class.
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

/**
 * Class Header
 *
 * @package EdidCreator
 */
class Header implements EdidAwareInterface
{
    /**
     * @param EdidBitFieldInterface $edid
     */
    public function __construct(EdidBitFieldInterface $edid)
    {
        $this->setEdid($edid);
    }
    /**
     * @return string
     */
    public function getHeader()
    {
        $offset = 0;
        $bitLength = 64; // 8 Bytes x 8 bits
        return $this->edid->getBitField($bitLength, $offset);
    }
    /**
     * @return integer[]
     */
    public function getHeaderAsByteIntegerArray()
    {
        return array_map('bindec', str_split($this->getHeader(), 8));
    }
    public function getHeaderAsPrefixedBinaryString()
    {
        return '0B' . $this->getHeader();
    }
    /**
     * @return string
     */
    public function getHeaderAsPrefixedHexadecimalIntegerString()
    {
        $bytes = array_map('dechex', $this->getHeaderAsByteIntegerArray());
        return '0X' . implode('', $bytes);
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
     * @throws \InvalidArgumentException
     * @throws \LengthException
     * @throws \DomainException
     * @return self
     */
    public function setHeader($value = '0X00FFFFFFFFFFFF00')
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
        $this->edid->setBitField($value, 0);
        return $this;
    }
    /**
     * @var EdidBitFieldInterface
     */
    private $edid;
    private $fieldLength = 64;
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
