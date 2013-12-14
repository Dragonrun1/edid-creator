<?php
/**
 * Contains BasicDisplayParameters class.
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
 * Class BasicDisplayParameters
 *
 * @package EdidCreator
 */
class BasicDisplayParameters extends AbstractEdidObject
{
    /**
     * Display gamma.
     *
     * value = (gamma * 100) - 100
     * Range is 1.00 - 3.54
     *
     * @var integer[integer]
     */
    private $displayGamma;
    /**
     * Maximum image horizontal size in centimeters (cm).
     *
     * If maximum size is unknown i.e. a projector this should be set to 0.
     * Size should be rounded to the nearest centimeter (cm).
     *
     * @var integer[integer]
     */
    private $maximumHorizontalImageSize;
    /**
     * Maximum image vertical size in centimeters.
     *
     * If maximum size is unknown i.e. a projector this should be set to 0.
     * Size should be rounded to the nearest centimeter (cm).
     *
     * @var integer[integer]
     */
    private $maximumVerticalImageSize;
    /**
     * Supported features bitmap.
     *
     * <pre>
     * Bit 7 = 1 DPMS standby supported.
     * Bit 6 = 1 DPMS suspend supported.
     * Bit 5 = 1 DPMS active-off supported.
     * Bit 4-3 = Display type (digital):
     *     00 = RGB 4:4:4
     *     01 = RGB 4:4:4 + YCrCb 4:4:4
     *     10 = RGB 4:4:4 + YCrCb 4:2:2
     *     11 = RGB 4:4:4 + YCrCb 4:4:4 + YCrCb 4:2:2
     * Bit 4-3 = Display type (analog):
     *     00 = Monochrome or Greyscale
     *     01 = RGB color
     *     10 = Non-RGB color
     *     11 = Undefined
     * Bit 2 = Standard sRGB colour space. Bytes 25–34 must contain sRGB
     * standard values.
     * Bit 1 = This bit specifies whether the preferred timing mode includes
     * native pixel format and refresh rate.
     * Bit 0 = GTF supported with default parameter values.
     * </pre>
     *
     * @link http://en.wikipedia.org/wiki/VESA_Display_Power_Management_Signaling
     * DPMS - VESA Display Power Management Signaling
     * @link http://en.wikipedia.org/wiki/SRGB sRGB - standard RGB color space
     * @link http://en.wikipedia.org/wiki/Generalized_Timing_Formula
     * GTF - Generalized Timing Formula
     *
     * @var integer[integer]
     */
    private $supportedFeatures;
    /**
     * Video input parameters bitmap.
     *
     * <pre>
     * Bit 7 = 1 Digital input and the following bit definitions apply:
     * Bit 6-1 = Reserved, must be 0.
     * Bit 0 = Signal is compatible with VESA DFP 1.x TMDS CRGB, 1 pixel per
     * clock, up to 8 bits per color, MSB aligned, DE active high.
     *
     * Bit 7 = 0 Use the following definitions for bit 6-0:
     * Bit 6-5 = Video white and sync levels, relative to blank:
     *     00 = +0.7/−0.3 V (1.000 V p-p)
     *     01 = +0.714/−0.286 V (1.000 V p-p)
     *     10 = +1.0/−0.4 V  (1.400 V p-p)
     *     11 = +0.7/0 V (0.700 V p-p) See EVC Std.
     * Bit 4 = 1 Blank-to-black setup (pedestal) expected.
     * Bit 3 = 1 Separate sync supported.
     * Bit 2 = 1 Composite sync (on HSync) supported.
     * Bit 1 = 1 Sync on green supported.
     * Bit 0 = 1 VSync pulse must be serrated when composite or sync-on-green is
     * used.
     * </pre>
     *
     * @var integer[integer]
     *
     * @link http://en.wikipedia.org/wiki/TMDS
     * Transition-minimized differential signaling
     * @link http://en.wikipedia.org/wiki/Sync_on_green Sync on green
     */
    private $videoInputParameters;
    /**
     * @return integer[integer]
     */
    public function getAllAsIntegerArray()
    {
        return array_merge(
            $this->getVideoInputParameters(),
            $this->getMaximumHorizontalImageSize(),
            $this->getMaximumVerticalImageSize(),
            $this->getDisplayGamma(),
            $this->getSupportedFeatures()
        );
    }
    /**
     * @return integer[integer]
     */
    public function getDisplayGamma()
    {
        return $this->displayGamma;
    }
    /**
     * @return integer[integer]
     */
    public function getMaximumHorizontalImageSize()
    {
        return $this->maximumHorizontalImageSize;
    }
    /**
     * @return integer[integer]
     */
    public function getMaximumVerticalImageSize()
    {
        return $this->maximumVerticalImageSize;
    }
    /**
     * @return integer[integer]
     */
    public function getSupportedFeatures()
    {
        return $this->supportedFeatures;
    }
    /**
     * @return integer[integer]
     */
    public function getVideoInputParameters()
    {
        return $this->videoInputParameters;
    }
    /**
     * @return bool
     */
    public function isAnalogSignalLevel()
    {
        return !$this->isDigitalSignalLevel();
    }
    /**
     * @return bool
     */
    public function isDigitalSignalLevel()
    {
        $mask = 0x80;
        $videoInputParameters = $this->getVideoInputParameters();
        if (($videoInputParameters[0] & $mask) != $mask) {
            return false;
        }
        return true;
    }
    /**
     * @param bool $trueOrFalse
     *
     * @return self
     * @throws \LogicException
     */
    public function setActiveOffFeature($trueOrFalse)
    {
        $trueOrFalse = (bool)$trueOrFalse;
        $setSupportedFeatures = $this->getSupportedFeatures();
        if ($trueOrFalse) {
            $this->setSupportedFeatures($setSupportedFeatures[0] | 0x20);
        } else {
            $this->setSupportedFeatures($setSupportedFeatures[0] & 0xDF);
        }
        return $this;
    }
    /**
     * @param bool $trueOrFalse
     *
     * @return self
     * @throws \LogicException
     */
    public function setCompositeSync($trueOrFalse)
    {
        if ($this->isDigitalSignalLevel()) {
            $mess = $this->methodToPropertyWords(__FUNCTION__)
                . 'is for analog use only';
            throw new \LogicException($mess);
        }
        $trueOrFalse = (bool)$trueOrFalse;
        $videoInputParameters = $this->getVideoInputParameters();
        if ($trueOrFalse) {
            $this->setVideoInputParameters($videoInputParameters[0] | 0x04);
        } else {
            $this->setVideoInputParameters($videoInputParameters[0] & 0xFB);
        }
        return $this;
    }
    /**
     * @param bool $trueOrFalse
     *
     * @return self
     * @throws \LogicException
     */
    public function setDefaultGtfSupportFeature($trueOrFalse)
    {
        $trueOrFalse = (bool)$trueOrFalse;
        $setSupportedFeatures = $this->getSupportedFeatures();
        if ($trueOrFalse) {
            $this->setSupportedFeatures($setSupportedFeatures[0] | 0x01);
        } else {
            $this->setSupportedFeatures($setSupportedFeatures[0] & 0xFE);
        }
        return $this;
    }
    /**
     * @param bool $trueOrFalse
     *
     * @return self
     * @throws \LogicException
     */
    public function setDfpCompatible($trueOrFalse)
    {
        if ($this->isAnalogSignalLevel()) {
            $mess = $this->methodToPropertyWords(__FUNCTION__)
                . 'is for digital use only';
            throw new \LogicException($mess);
        }
        $trueOrFalse = (bool)$trueOrFalse;
        $videoInputParameters = $this->getVideoInputParameters();
        if ($trueOrFalse) {
            $this->setVideoInputParameters($videoInputParameters[0] | 0x01);
        } else {
            $this->setVideoInputParameters($videoInputParameters[0] & 0xFE);
        }
        return $this;
    }
    /**
     * @param int|float|integer[integer]|null $value
     *
     * @return self
     * @throws \InvalidArgumentException
     * @throws \DomainException
     * @throws \LengthException
     */
    public function setDisplayGamma($value = null)
    {
        if (is_null($value)) {
            unset($this->displayGamma);
            return $this;
        }
        if (is_float($value)) {
            $value = abs($value);
            $minGamma = 1.00;
            $maxGamma = 3.54;
            if ($value < $minGamma || $value > $maxGamma) {
                $mess = 'Display gamma has to be between 1.00 and 3.54';
                throw new \DomainException($mess);
            }
            $value = (array)(($value * 100) - 100);
        }
        $this->displayGamma =
            $this->convertToSingleByteArray($value, __FUNCTION__);
        return $this;
    }
    /**
     * @param integer $mode
     *
     * @return self
     * @throws \LogicException
     */
    public function setDisplayTypeFeature($mode)
    {
        $mode = ($mode & 0x03) << 3;
        $mask = 0xE7;
        $setSupportedFeatures = $this->getSupportedFeatures();
        $this->setSupportedFeatures(
             $setSupportedFeatures[0] & $mask | $mode
        );
        return $this;
    }
    /**
     * @param int|integer[integer]|null $value
     *
     * @return self
     * @throws \InvalidArgumentException
     * @throws \LengthException
     */
    public function setMaximumHorizontalImageSize($value = null)
    {
        if (is_null($value)) {
            unset($this->maximumHorizontalImageSize);
            return $this;
        }
        $this->maximumHorizontalImageSize =
            $this->convertToSingleByteArray($value, __FUNCTION__);
        return $this;
    }
    /**
     * @param int|integer[integer]|null $value
     *
     * @return self
     * @throws \InvalidArgumentException
     * @throws \LengthException
     */
    public function setMaximumVerticalImageSize($value = null)
    {
        if (is_null($value)) {
            unset($this->maximumVerticalImageSize);
            return $this;
        }
        $this->maximumVerticalImageSize =
            $this->convertToSingleByteArray($value, __FUNCTION__);
        return $this;
    }
    /**
     * @param bool $trueOrFalse
     *
     * @return self
     * @throws \LogicException
     */
    public function setPreferredTimingModeFeature($trueOrFalse)
    {
        $trueOrFalse = (bool)$trueOrFalse;
        $setSupportedFeatures = $this->getSupportedFeatures();
        if ($trueOrFalse) {
            $this->setSupportedFeatures($setSupportedFeatures[0] | 0x02);
        } else {
            $this->setSupportedFeatures($setSupportedFeatures[0] & 0xFD);
        }
        return $this;
    }
    /**
     * @param bool $trueOrFalse
     *
     * @return self
     * @throws \LogicException
     */
    public function setSeparateSync($trueOrFalse)
    {
        if ($this->isDigitalSignalLevel()) {
            $mess = $this->methodToPropertyWords(__FUNCTION__)
                . 'is for analog use only';
            throw new \LogicException($mess);
        }
        $trueOrFalse = (bool)$trueOrFalse;
        $videoInputParameters = $this->getVideoInputParameters();
        if ($trueOrFalse) {
            $this->setVideoInputParameters($videoInputParameters[0] | 0x08);
        } else {
            $this->setVideoInputParameters($videoInputParameters[0] & 0xF7);
        }
        return $this;
    }
    /**
     * @param bool $trueOrFalse
     *
     * @return self
     * @throws \LogicException
     */
    public function setSerratedSync($trueOrFalse)
    {
        if ($this->isDigitalSignalLevel()) {
            $mess = $this->methodToPropertyWords(__FUNCTION__)
                . 'is for analog use only';
            throw new \LogicException($mess);
        }
        $trueOrFalse = (bool)$trueOrFalse;
        $videoInputParameters = $this->getVideoInputParameters();
        if ($trueOrFalse) {
            $this->setVideoInputParameters($videoInputParameters[0] | 0x01);
        } else {
            $this->setVideoInputParameters($videoInputParameters[0] & 0xFE);
        }
        return $this;
    }
    /**
     * @param bool $trueOrFalse
     *
     * @return self
     * @throws \LogicException
     */
    public function setSetupPedestal($trueOrFalse)
    {
        if ($this->isDigitalSignalLevel()) {
            $mess = $this->methodToPropertyWords(__FUNCTION__)
                . 'is for analog use only';
            throw new \LogicException($mess);
        }
        $trueOrFalse = (bool)$trueOrFalse;
        $videoInputParameters = $this->getVideoInputParameters();
        if ($trueOrFalse) {
            $this->setVideoInputParameters($videoInputParameters[0] | 0x10);
        } else {
            $this->setVideoInputParameters($videoInputParameters[0] & 0xEF);
        }
        return $this;
    }
    /**
     * @param string $mode
     *
     * @return self
     * @throws \DomainException
     */
    public function setSignalLevel($mode = 'digital')
    {
        if ($mode == 'digital') {
            $mask = 0x01;
            $signalLevel = 0x80;
        } elseif ($mode == 'analog') {
            $mask = 0x7F;
            $signalLevel = 0x00;
        } else {
            $mess =
                'Signal level can only be "digital" or "analog" but received '
                . $mode;
            throw new \DomainException($mess);
        }
        $videoInputParameters = $this->getVideoInputParameters();
        $this->setVideoInputParameters(
             $videoInputParameters[0] & $mask | $signalLevel
        );
        return $this;
    }
    /**
     * @param integer $mode
     *
     * @return self
     * @throws \LogicException
     */
    public function setSignalLevelStandard($mode)
    {
        if ($this->isDigitalSignalLevel()) {
            $mess = $this->methodToPropertyWords(__FUNCTION__)
                . 'is for analog use only';
            throw new \LogicException($mess);
        }
        $mode = ($mode & 0x03) << 5;
        $mask = 0x9F;
        $videoInputParameters = $this->getVideoInputParameters();
        $this->setVideoInputParameters(
             $videoInputParameters[0] & $mask | $mode
        );
        return $this;
    }
    /**
     * @param bool $trueOrFalse
     *
     * @return self
     * @throws \LogicException
     */
    public function setStandardColorSpaceFeature($trueOrFalse)
    {
        $trueOrFalse = (bool)$trueOrFalse;
        $setSupportedFeatures = $this->getSupportedFeatures();
        if ($trueOrFalse) {
            $this->setSupportedFeatures($setSupportedFeatures[0] | 0x04);
        } else {
            $this->setSupportedFeatures($setSupportedFeatures[0] & 0xFB);
        }
        return $this;
    }
    /**
     * @param bool $trueOrFalse
     *
     * @return self
     * @throws \LogicException
     */
    public function setStandbyFeature($trueOrFalse)
    {
        $trueOrFalse = (bool)$trueOrFalse;
        $setSupportedFeatures = $this->getSupportedFeatures();
        if ($trueOrFalse) {
            $this->setSupportedFeatures($setSupportedFeatures[0] | 0x80);
        } else {
            $this->setSupportedFeatures($setSupportedFeatures[0] & 0x7F);
        }
        return $this;
    }
    /**
     * @param int|integer[integer]|null $value
     *
     * @return self
     */
    public function setSupportedFeatures($value = null)
    {
        if (is_null($value)) {
            unset($this->supportedFeatures);
            return $this;
        }
        $this->supportedFeatures =
            $this->convertToSingleByteArray($value, __FUNCTION__);
        return $this;
    }
    /**
     * @param bool $trueOrFalse
     *
     * @return self
     * @throws \LogicException
     */
    public function setSuspendFeature($trueOrFalse)
    {
        $trueOrFalse = (bool)$trueOrFalse;
        $setSupportedFeatures = $this->getSupportedFeatures();
        if ($trueOrFalse) {
            $this->setSupportedFeatures($setSupportedFeatures[0] | 0x40);
        } else {
            $this->setSupportedFeatures($setSupportedFeatures[0] & 0xBF);
        }
        return $this;
    }
    /**
     * @param bool $trueOrFalse
     *
     * @return self
     * @throws \LogicException
     */
    public function setSyncOnGreen($trueOrFalse)
    {
        if ($this->isDigitalSignalLevel()) {
            $mess = $this->methodToPropertyWords(__FUNCTION__)
                . 'is for analog use only';
            throw new \LogicException($mess);
        }
        $trueOrFalse = (bool)$trueOrFalse;
        $videoInputParameters = $this->getVideoInputParameters();
        if ($trueOrFalse) {
            $this->setVideoInputParameters($videoInputParameters[0] | 0x02);
        } else {
            $this->setVideoInputParameters($videoInputParameters[0] & 0xFD);
        }
        return $this;
    }
    /**
     * @param int|integer[integer]|null $value
     *
     * @return self
     */
    public function setVideoInputParameters($value = null)
    {
        if (is_null($value)) {
            unset($this->videoInputParameters);
            return $this;
        }
        $this->videoInputParameters =
            $this->convertToSingleByteArray($value, __FUNCTION__);
        return $this;
    }
}
