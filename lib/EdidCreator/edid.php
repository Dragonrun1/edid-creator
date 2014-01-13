<?php
/**
 * Contains Edid class.
 *
 * PHP version 5.3
 *
 * LICENSE:
 * This name is part of Edid Creator which can be used to create a version 1.3 Extended Display Identification Data
 * binary name.
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
 * You should be able to find a copy of this license in the LICENSE.md name. A copy of the GNU GPL should also be
 * available in the GNU-GPL.md name.
 *
 * @author    Michael Cummings <mgcummings@yahoo.com>
 * @copyright 2013 Michael Cummings
 * @license   http://www.gnu.org/copyleft/lesser.html GNU LGPL
 */
namespace EdidCreator;

/**
 * Class Edid
 *
 * @package EdidCreator
 */
class Edid implements EdidBitFieldInterface, EdidComponentInterface
{
    /**
     * 128 Bytes * 8 value
     */
    const MAX_BIT_FIELD_LENGTH = 1024;
    /**
     * @param string|null $edid
     */
    public function __construct($edid = null)
    {
        $this->setEdid($edid);
    }
    /**
     * @param integer       $bitLength
     * @param integer|array $offset
     *
     * @throws \InvalidArgumentException
     * @throws \OverflowException
     * @return string
     */
    public function getBitField($bitLength, $offset)
    {
        if (!is_int($bitLength)) {
            $mess = 'Can only use integer for $bitLength';
            throw new \InvalidArgumentException($mess);
        }
        if (is_array($offset)) {
            $offset = ($offset[0] << 3) | ($offset[1] & 0x07);
        } elseif (!is_int($offset)) {
            $mess = '$offset MUST be an integer or integer array but received '
                . gettype($offset);
            throw new \InvalidArgumentException($mess);
        }
        $bitLength = abs((int)$bitLength);
        $offset = abs((int)$offset);
        if (($bitLength + $offset) > Edid::MAX_BIT_FIELD_LENGTH) {
            $mess = '$bitLength plus $offset exceeds maximum field length';
            throw new \OverflowException($mess);
        }
        $bits = substr($this->getEdid(), -($bitLength + $offset), $bitLength);
        return $bits;
    }
    /**
     * @return string
     * @throws \LogicException
     */
    public function getEdid()
    {
        if (strlen($this->edid) < Edid::MAX_BIT_FIELD_LENGTH) {
            $mess = 'Edid has not been initialized yet';
            throw new \LogicException($mess);
        }
        return $this->edid;
    }
    /**
     * @param string            $value
     * @param integer|integer[] $offset
     *
     * @throws \InvalidArgumentException
     * @throws \OverflowException
     * @throws \DomainException
     * @return self
     */
    public function setBitField($value, $offset)
    {
        if (!is_string($value)) {
            $mess = 'Expected string for $value but got ' . gettype($value);
            throw new \InvalidArgumentException($mess);
        }
        $bitLength = strlen($value);
        if ($bitLength != strspn($value, '01')) {
            $mess = '$value is NOT a binary integer string';
            throw new \DomainException($mess);
        }
        if (is_array($offset)) {
            $offset = ($offset[0] << 3) | ($offset[1] & 0x07);
        } elseif (!is_int($offset)) {
            $mess = '$offset MUST be an integer or integer array but received '
                . gettype($offset);
            throw new \InvalidArgumentException($mess);
        }
        $offset = abs((int)$offset);
        if (($bitLength + $offset) > Edid::MAX_BIT_FIELD_LENGTH) {
            $mess = '$value length plus $offset exceeds maximum field length';
            throw new \OverflowException($mess);
        }
        $this->setEdid(
             substr_replace(
                 $this->getEdid(),
                 $value,
                 -($bitLength + $offset),
                 $bitLength
             )
        );
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
    public function setEdid($value = null)
    {
        if (is_null($value)) {
            $value = str_repeat('0', Edid::MAX_BIT_FIELD_LENGTH);
        }
        if (!is_string($value)) {
            $mess = 'Expected string but got ' . gettype($value);
            throw new \InvalidArgumentException($mess);
        }
        $value =
            str_pad($value, Edid::MAX_BIT_FIELD_LENGTH, '0', STR_PAD_LEFT);
        $vCount = strlen($value);
        if ($vCount != strspn($value, '01')) {
            $mess = '$value must be non-prefixed binary bit field string';
            throw new \DomainException($mess);
        }
        if ($vCount != Edid::MAX_BIT_FIELD_LENGTH) {
            $mess = 'Binary integer MUST be '
                . Edid::MAX_BIT_FIELD_LENGTH
                . ' bits long but is instead '
                . $vCount;
            throw new \LengthException($mess);
        }
        $this->edid = $value;
        return $this;
    }
    /**
     * @param string $name
     *
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @throws \DomainException
     * @return self
     */
    public function setEdidFromBinaryFile($name)
    {
        if (!is_string($name)) {
            $mess = '$name MUST be string but received ' . gettype($name);
            throw new \InvalidArgumentException($mess);
        }
        $fh = @fopen($name, 'rb');
        if (false === $fh) {
            $mess = 'Could NOT open for reading ' . $name;
            throw new \DomainException($mess);
        }
        if (feof($fh)) {
            @fclose($fh);
            $mess = 'File is empty ' . $name;
            throw new \DomainException($mess);
        }
        $bytes = array();
        $hardLimit = 128;
        $bCount = 0;
        while (false !== ($byte = fgetc($fh)) && ++$bCount <= $hardLimit) {
            $bytes[] = str_pad(decbin(ord($byte)), 8, '0', STR_PAD_LEFT);
        }
        if (false === @fclose($fh)) {
            $mess = 'Failed to close ' . $name;
            throw new \RuntimeException($mess);
        }
        $this->setEdid(implode('', array_reverse($bytes)));
        return $this;
    }
    /**
     * Used to update the checksum value of the Edid.
     */
    public function updateChecksum()
    {
        $bytes = array_reverse(str_split($this->getEdid(), 8));
        $bytes[127] = '00000000';
        $checksum = 256 - array_reduce(
                $bytes,
                function ($sum, $value) {
                    return ($sum + bindec($value)) & 255;
                },
                0
            );
        //$checksum = 256 - array_reduce($bytes, array($this, 'checksum'), 0);
        $checksum = str_pad(decbin($checksum), 8, '0', STR_PAD_LEFT);
        $this->setBitField($checksum, array(127, 0));
        return $this;
    }
    /**
     * @param string $name
     *
     * @throws \RuntimeException
     * @throws \RangeException
     * @throws \InvalidArgumentException
     * @throws \DomainException
     * @return self
     */
    public function writeEdidToBinaryFile($name)
    {
        if (!is_string($name)) {
            $mess = '$name MUST be string but received ' . gettype($name);
            throw new \InvalidArgumentException($mess);
        }
        $fh = @fopen($name, 'wb');
        if (false === $fh) {
            $mess = 'Could NOT open for writing ' . $name;
            throw new \DomainException($mess);
        }
        $this->updateChecksum();
        $bytes = array_reverse(str_split($this->getEdid(), 8));
        for ($i = 0, $bCount = count($bytes);$i < $bCount;
            ++$i) {
            if (false === fwrite($fh, chr(bindec($bytes[$i])), 1)) {
                @fflush($fh);
                @fclose($fh);
                $mess = 'Write failed at byte ' . $i . ' for ' . $name;
                throw new \RangeException($mess);
            }
        }
        if (false === @fflush($fh)) {
            $mess = 'Failed to flush ' . $name;
            throw new \RuntimeException($mess);
        }
        if (false === @fclose($fh)) {
            $mess = 'Failed to close ' . $name;
            throw new \RuntimeException($mess);
        }
        return $this;
    }
    /**
     * @var string
     */
    private $edid;
    /**
     * @return string
     */
    public function __toString()
    {
        $method = 'get' . basename(__CLASS__);
        return $this->$method();
    }
    /**
     * @param string $value
     *
     * @throws \InvalidArgumentException
     * @throws \LengthException
     * @throws \DomainException
     * @return string
     */
    public function __invoke($value)
    {
        $method = 'set' . basename(__CLASS__);
        return $this->$method($value);
    }
}
