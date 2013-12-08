<?php
/**
 * Contains HeaderInformation class.
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
 * Class HeaderInformation
 *
 * @package Edid
 */
class HeaderInformation
{
    /**
     * @var integer[integer]
     */
    private $edidVersion = array(0x01, 0x03);
    /**
     * @var integer[integer]
     */
    private $headerPattern = array(
        0x00,
        0xFF,
        0xFF,
        0xFF,
        0xFF,
        0xFF,
        0xFF,
        0x00
    );
    /**
     * @var integer[integer]
     */
    private $manufacturerId;
    /**
     * @var integer[integer]
     */
    private $productCode;
    /**
     * @var integer[integer]
     */
    private $serialNumber;
    /**
     * @var integer[integer]
     *
     * @link http://en.wikipedia.org/wiki/Week_number#Week_numbering
     * Week Numbering
     */
    private $weekOfManufacture;
    /**
     * @var integer[integer]
     */
    private $yearOfManufacture;
    /**
     * @param string|integer|integer[integer]|null $manufacturerId
     * @param integer|integer[integer]|null $productCode
     * @param integer|integer[integer]|null $serialNumber
     * @param integer|null $weekOfManufacture
     * @param integer|null $yearOfManufacture
     */
    public function __construct(
        $manufacturerId = null,
        $productCode = null,
        $serialNumber = null,
        $weekOfManufacture = null,
        $yearOfManufacture = null
    ) {
        $this->setManufacturerId($manufacturerId);
        $this->setProductCode($productCode);
        $this->setWeekOfManufacture($weekOfManufacture);
        $this->setYearOfManufacture($yearOfManufacture);
    }
    /**
     * @return integer[integer]
     */
    public function getAllAsIntegerArray()
    {
        return array_merge(
            $this->getHeaderPattern(),
            $this->getManufacturerId(),
            $this->getProductCode(),
            $this->getSerialNumber(),
            $this->getWeekOfManufacture(),
            $this->getYearOfManufacture(),
            $this->getEdidVersion()
        );
    }
    /**
     * @return integer[integer]
     */
    public function getEdidVersion()
    {
        return $this->edidVersion;
    }
    /**
     * @return integer[integer]
     */
    public function getHeaderPattern()
    {
        return $this->headerPattern;
    }
    /**
     * @return integer[integer]
     * @throws \LogicException
     */
    public function getManufacturerId()
    {
        if (empty($this->manufacturerId)) {
            $mess = 'Manufacturer Id has NOT been set';
            throw new \LogicException($mess);
        }
        return $this->manufacturerId;
    }
    /**
     * @return integer[integer]
     * @throws \LogicException
     */
    public function getProductCode()
    {
        if (empty($this->productCode)) {
            $mess = 'Product code has NOT been set';
            throw new \LogicException($mess);
        }
        return $this->productCode;
    }
    /**
     * @return integer[integer]
     * @throws \LogicException
     */
    public function getSerialNumber()
    {
        if (empty($this->serialNumber)) {
            $mess = 'Serial number has NOT been set';
            throw new \LogicException($mess);
        }
        return $this->serialNumber;
    }
    /**
     * @return integer[]
     * @throws \LogicException
     */
    public function getWeekOfManufacture()
    {
        if (empty($this->weekOfManufacture)) {
            $mess = 'Week of manufacture has NOT been set';
            throw new \LogicException($mess);
        }
        return $this->weekOfManufacture;
    }
    /**
     * @return integer[]
     * @throws \LogicException
     */
    public function getYearOfManufacture()
    {
        if (empty($this->yearOfManufacture)) {
            $mess = 'Year of manufacture has NOT been set';
            throw new \LogicException($mess);
        }
        return $this->yearOfManufacture;
    }
    /**
     * @param string|integer|integer[integer]|null $manufacturerId
     *
     * @return self
     * @throws \InvalidArgumentException
     * @throws \LengthException
     */
    public function setManufacturerId($manufacturerId = null)
    {
        if (is_null($manufacturerId)) {
            unset($this->manufacturerId);
            return $this;
        }
        if (is_string($manufacturerId)) {
            $manufacturerId =
                $this->convertStringManufacturerIdToArray($manufacturerId);
        }
        if (is_int($manufacturerId)) {
            $manufacturerId =
                $this->convertIntegerManufacturerIdToArray($manufacturerId);
        } elseif (!is_array($manufacturerId)) {
            $mess =
                'Manufacturer Id must be an array or convertible to an array';
            throw new \InvalidArgumentException($mess);
        }
        if (count($manufacturerId) != 2) {
            $mess = 'Manufacturer Id must have a length equal to 2';
            throw new \LengthException($mess);
        }
        $manufacturerId = array_values($manufacturerId); // Re-index
        $manufacturerId[0] = (int)$manufacturerId[0] & 0xFF;
        $manufacturerId[1] = (int)$manufacturerId[1] & 0x7F;
        $this->manufacturerId = $manufacturerId;
        return $this;
    }
    /**
     * @param integer|integer[integer]|null $productCode
     *
     * @return self
     * @throws \InvalidArgumentException
     * @throws \LengthException
     */
    public function setProductCode($productCode = null)
    {
        if (is_null($productCode)) {
            unset($this->productCode);
            return $this;
        }
        if (is_int($productCode)) {
            $temp = array();
            $temp[] = (int)$productCode & 0xFF;
            $temp[] = (int)$productCode >> 8;
            $productCode = $temp;
        } elseif (!is_array($productCode)) {
            $mess = 'Product code must be an integer or an array';
            throw new \InvalidArgumentException($mess);
        }
        if (count($productCode) != 2) {
            $mess = 'Product code must have a length equal to 2';
            throw new \LengthException($mess);
        }
        $productCode = array_values($productCode); // Re-index
        $this->byteMaskArray($productCode);
        $this->productCode = $productCode;
        return $this;
    }
    /**
     * @param integer|integer[integer]|null $serialNumber
     *
     * @return self
     * @throws \InvalidArgumentException
     * @throws \LengthException
     */
    public function setSerialNumber($serialNumber = null)
    {
        if (is_null($serialNumber)) {
            unset($this->serialNumber);
            return $this;
        }
        if (is_int($serialNumber)) {
            $temp = array();
            $temp[] = $serialNumber;
            $temp[] = $serialNumber >> 8;
            $temp[] = $serialNumber >> 16;
            $temp[] = $serialNumber >> 24;
            $serialNumber = $temp;
        } elseif (!is_array($serialNumber)) {
            $mess = 'Serial number must an array or an integer';
            throw new \InvalidArgumentException($mess);
        }
        if (count($serialNumber) != 4) {
            $mess = 'Serial number must have a length of 4';
            throw new \LengthException($mess);
        }
        $serialNumber = array_values($serialNumber); // Re-index
        $this->byteMaskArray($serialNumber);
        $this->serialNumber = $serialNumber;
        return $this;
    }
    /**
     * @param integer|null $weekOfManufacture
     *
     * @return self
     * @throws \DomainException
     */
    public function setWeekOfManufacture($weekOfManufacture = null)
    {
        if (is_null($weekOfManufacture)) {
            unset($this->weekOfManufacture);
            return $this;
        }
        $weekOfManufacture = abs((int)$weekOfManufacture);
        if ($weekOfManufacture < 0 || $weekOfManufacture > 53) {
            $mess = 'Week of manufacturer has to be between 0 and 53';
            throw new \DomainException($mess);
        }
        $this->weekOfManufacture = (array)$weekOfManufacture;
        return $this;
    }
    /**
     * @param integer|null $yearOfManufacture
     *
     * @return self
     * @throws \DomainException
     */
    public function setYearOfManufacture($yearOfManufacture = null)
    {
        if (is_null($yearOfManufacture)) {
            unset($this->yearOfManufacture);
            return $this;
        }
        $yearOfManufacture = abs((int)$yearOfManufacture);
        $minYear = 1990;
        $maxYear = 2245;
        if ($yearOfManufacture >= $minYear
            && $yearOfManufacture <= $maxYear
        ) {
            $yearOfManufacture -= $minYear;
        }
        if ($yearOfManufacture > 255) {
            $mess = 'Year of manufacturer can only be between 1990 and 2245';
            throw new \DomainException($mess);
        }
        $this->yearOfManufacture = (array)$yearOfManufacture;
        return $this;
    }
    /**
     * @param integer[] $array
     */
    private function byteMaskArray(array &$array)
    {
        foreach ($array as &$value) {
            $value &= 0xFF;
        }
    }
    /**
     * @param integer $manufacturerId
     *
     * @return array
     */
    private function convertIntegerManufacturerIdToArray($manufacturerId)
    {
        $id = abs($manufacturerId);
        $manufacturerId = array();
        $manufacturerId[] = $id;
        $manufacturerId[] = $id >> 8;
        return $manufacturerId;
    }
    /**
     * @param string $manufacturerId
     *
     * @return int
     * @throws \InvalidArgumentException
     * @throws \LengthException
     */
    private function convertStringManufacturerIdToArray($manufacturerId)
    {
        $ordA = 65;
        $ordZ = 90;
        $ordAMinusOne = $ordA - 1;
        if (strlen($manufacturerId != 3)) {
            $mess =
                'Manufacturer Id has to be 3 characters long when using string';
            throw new \LengthException($mess);
        }
        $manufacturerId = strtoupper($manufacturerId);
        $bitmap = 0;
        for ($i = 0;$i < 3;++$i) {
            $ord = ord($manufacturerId[$i]);
            if ($ord < $ordA || $ord > $ordZ) {
                $mess =
                    'Manufacturer Ids can only be the letters A-Z or a-z when using string';
                throw new \InvalidArgumentException($mess);
            }
            $bitmap = ($bitmap << 5) | ($ord - $ordAMinusOne);
        }
        return (int)$bitmap;
    }
}
