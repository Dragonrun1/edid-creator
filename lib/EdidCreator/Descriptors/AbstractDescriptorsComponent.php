<?php
/**
 * Contains AbstractDescriptorsComponent class.
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
namespace EdidCreator\Descriptors;

use EdidCreator\Edid;
use EdidCreator\EdidAwareInterface;
use EdidCreator\EdidBitFieldInterface;
use EdidCreator\EdidComponentInterface;

/**
 * Class AbstractDescriptorsComponent
 *
 * @package EdidCreator\Descriptors
 */
abstract class AbstractDescriptorsComponent implements EdidAwareInterface, EdidComponentInterface
{
    /**
     * @param int                        $position
     * @param EdidBitFieldInterface|null $edid
     */
    public function __construct(
        $position = 0,
        EdidBitFieldInterface $edid = null
    ) {
        $this->setByteOffset($position);
        $this->setEdid($edid);
    }
    /**
     * @return int
     */
    public function getByteOffset()
    {
        return ($this->byteOffset - $this->offsetBase) % 18;
    }
    /**
     * @param int $value
     *
     * @throws \InvalidArgumentException
     * @return self
     */
    public function setByteOffset($value = 0)
    {
        if (!is_int($value)) {
            $mess = '$value MUST be an integer but received ' . gettype($value);
            throw new \InvalidArgumentException($mess);
        }
        $this->byteOffset = ((abs($value) & 3) * 18) + $this->offsetBase;
        return $this;
    }
    /**
     * @return EdidBitFieldInterface
     */
    public function getEdid()
    {
        return $this->edid;
    }
    /**
     * @param EdidBitFieldInterface|null $edid
     *
     * @return self
     */
    public function setEdid(EdidBitFieldInterface $edid = null)
    {
        if (is_null($edid)) {
            $edid = new Edid();
        }
        $this->edid = $edid;
        return $this;
    }
    /**
     * @var int
     */
    protected $byteOffset;
    /**
     * @var EdidBitFieldInterface
     */
    protected $edid;
    /**
     * @var int
     */
    protected $offsetBase = 54;
    /**
     * @param string|int $value
     * @param int        $fieldLength
     *
     * @throws \InvalidArgumentException
     * @throws \LengthException
     * @throws \DomainException
     * @return string
     */
    protected function convertValueToBitString($value, $fieldLength = 144)
    {
        if (!is_int($fieldLength)) {
            $mess = '$fieldLength MUST be an integer but received ' . gettype($value);
            throw new \InvalidArgumentException($mess);
        }
        if (is_int($value)) {
            $value = str_pad(decbin(abs($value)), $fieldLength, '0', STR_PAD_LEFT);
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
            $mess = '$value MUST be a string or an integer but received ' . gettype($value);
            throw new \InvalidArgumentException($mess);
        }
        $vCount = strlen($value);
        if ($vCount != $fieldLength) {
            $mess = 'Binary integer MUST be ' . $fieldLength . ' bits long but is instead ' . $vCount;
            throw new \LengthException($mess);
        }
        return $value;
    }
    /**
     * @param string $value
     * @param int    $padLength
     *
     * @return string
     */
    protected function hexadecimalStringToBinaryString($value, $padLength = 4)
    {
        $value = str_split($value);
        $bits = '';
        foreach ($value as $v) {
            $bits .= str_pad(decbin(hexdec($v)), $padLength, '0', STR_PAD_LEFT);
        }
        return $bits;
    }
}
