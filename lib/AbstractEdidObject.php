<?php
/**
 * Contains AbstractEdidObject class.
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
 * Class AbstractEdidObject
 *
 * @package EdidCreator
 */
abstract class AbstractEdidObject
{
    /**
     * @param int|integer[integer] $value
     * @param string $method
     *
     * @return integer[]
     * @throws \InvalidArgumentException
     * @throws \LengthException
     */
    protected function convertToSingleByteArray($value, $method)
    {
        if (is_int($value)) {
            $value = (array)(abs($value));
        } elseif (!is_array($value)) {
            $mess = $this->methodToPropertyWords($method)
                . ' must be an array or convertible to an array';
            throw new \InvalidArgumentException($mess);
        }
        if (count($value) != 1) {
            $mess = $this->methodToPropertyWords($method)
                . ' must have a length equal to 1';
            throw new \LengthException($mess);
        }
        $value = array_values($value);
        $value[0] &= 0xFF;
        return $value;
    }
    /**
     * Converts camelCase method name to lower case string with first letter on
     * first word upper cased and have separator character(s) between each word.
     *
     * The first word is also dropped so method name like 'setDisplayGamma'
     * becomes 'Display gamma'
     *
     * @param string $method
     * @param string $join
     *
     * @return string
     */
    protected function methodToPropertyWords($method, $join = ' ')
    {
        $re = '/(?<=[a-z])(?=[A-Z])/x';
        $a = preg_split($re, $method);
        array_shift($a);
        return ucfirst(strtolower(implode($join, $a)));
    }
}
