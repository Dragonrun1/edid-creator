<?php
/**
 * Contains EdidComponentInterface Interface.
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
 * Interface EdidComponentInterface
 *
 * @package EdidCreator
 */
interface EdidComponentInterface
{
    /**
     * @param string $value
     *
     * @throws \InvalidArgumentException
     * @throws \LengthException
     * @throws \DomainException
     * @return string
     */
    public function __invoke($value);
    /**
     * @return string
     */
    public function __toString();
}
