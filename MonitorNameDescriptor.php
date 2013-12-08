<?php
/**
 * Contains MonitorNameDescriptor class.
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

class MonitorNameDescriptor
{
    /**
     * @var integer[]
     */
    private $header = array(0x00, 0x00, 0xFC, 0x00);
    /**
     * @var string
     */
    private $name;
    /**
     * @return integer[integer]
     */
    public function getAllAsIntegerArray()
    {
        $name = $this->getName();
        if (strlen($name) < 13) {
            $name = str_split(str_pad($name . "\n", 13));
        }
        return array_merge(
            $this->getHeader(),
            $name
        );
    }
    /**
     * @return integer[]
     */
    public function getHeader()
    {
        return $this->header;
    }
    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * @param string $name
     *
     * @return self
     * @throws \LengthException
     */
    public function setName($name)
    {
        $name = (string)$name;
        if (strlen($name) > 13) {
            $mess = 'Name can be no more than 13 characters long';
            throw new \LengthException($mess);
        }
        $this->name = $name;
        return $this;
    }
}
