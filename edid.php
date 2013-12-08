<?php
/**
 * Created by PhpStorm.
 * User: Dragon
 * Date: 11/25/13
 * Time: 6:09 AM
 */
// HeaderInformation
$edid =
    '00000000' . '11111111' . '11111111' . '11111111' . '11111111' . '11111111'
    . '11111111' . '00000000';
// manufacturerId
$edid .= '0' . '00001' . '00011' . '10010'; // ACR
// productId
$edid .= '10100110' . '00000001'; // 0x01A6 (little endian)
// serialNumber
$edid .= '10000001' . '01001000' . '00110000' .  '00100000'; // little-endian
// week
$edid .= '00000011'; // 3
// year
$edid .= '00010110'; // 2012
// edid Version 1.3
$edid .= '00000001' . '00000011';
// Basic display parameters
// Video input Digital, DFP = No
$edid .= '10000000';
$edid .= '00110011'; // 51cm Max horizontal image size
$edid .= '00011101'; // 29cm Max vertical size
$edid .= '01111000'; // 2.2 Gamma
// Supported Features
$edid .= '001'; // DPMS Standby, Suspend, Active-Off / Low Power
$edid .= '01'; // Display type RGB 4:4:4 + YCrCb 4:4:4 (digital)
$edid .= '1'; // standardsRGBColorSpace Must have bytes 25-34 set
$edid .= '1'; // nativePixelFormat First detailed timing mode is native pixel format
$edid .= '0'; // No GTF support
// Chromaticity coordinates
 // 0x77 0xC5 0xA5 0x57 0x52, 0x9C 0x25 0x11 0x50 0x54
$edid .=
    '01110111' . '11000101' . '10100101' . '01010111' . '01010010'
    . '10011100' . '00100101' . '00010001' . '01010000' . '01010100';
// Established timing bitmap support
$edid .= '00101111' . '11101111'. '10000000';
// Standard Timing
// 1152 4:3 75Hz
$edid .= '01110001' . '01001111';
// 1280 16:9 60Hz
$edid .= '10000001' . '11000000';
// 1280 4:3 60Hz
$edid .= '10000001' . '01000000';
// 1280 5:4 60Hz
$edid .= '10000001' . '10000000';
// 1280 16:10 60Hz
$edid .= '10000001' . '00000000';
// 1920 16:9 60Hz
$edid .= '11010001' . '11000000';
// 1920 4:3 60Hz
$edid .= '11010001' . '01000000';
// Unused
$edid .= '00000001' . '00000001';
// Detailed Timing Descriptors
// ModeLine      "1920x1080@60DT" 148.50 1920 2008 2052 2200 1080 1084 1088 1125 -HSync -VSync
//////////////////////////////////////////////////////////////////////////////////////////////
// 0x04 0x3A 0x80 0x18 0x71 0x38 0x2D 0x40 0x58 0x2C 0x44 0x00 0xFE 0x1F 0x11 0x00 0x00 0x1A
//////////////////////////////////////////////////////////////////////////////////////////////
// Dot clock 148.50MHz 0x04 0x3A
$edid .= '00000010' . '00111010';
// H active pixels 8 ls bits 0x80
$edid .= '10000000';
// H blanking 8 ls bits 0x18
$edid .= '00011000';
// H active / blanking pixels 4 / 4 ms bits 0x71
$edid .= '01110001';
// V active pixels 8 ls bits 0x38
$edid .= '00111000';
// V blanking 8 ls bits 0x2D
$edid .= '00101101';
// V active / blanking pixels 4 / 4 ms bits 0x40
$edid .= '01000000';
// H sync offset pixels 8 ls bits 0x58
$edid .= '01011000';
// H sync pulse width pixels 8 ls bits 0x2C
$edid .= '00101100';
// V sync offset lines 4 ls bits 0x44
$edid .= '0100';
// V sync pulse width lines 4 ls bits
$edid .= '0100';
// H sync offset pixels 2 ms bits 0x00
$edid .= '00';
// H sync pulse width pixels 2 ms bits
$edid .= '00';
// V sync offset lines 2 ms bits
$edid .= '00';
// V sync pulse width lines 2 ms bits
$edid .= '00';
// H display size, mm, 8 ls bits 0xFE
$edid .= '11111110';
// V display size, mm, 8 ls bits 0x1F
$edid .= '00011111';
// H display size, mm, 4 ms bits 0x11
$edid .= '0001';
// V display size, mm, 4 ms bits
$edid .= '0001';
// H border pixels 0x00
$edid .= '00000000';
// V border lines 0x00
$edid .= '00000000';
// Features bitmap
// Interlaced 0x1A
$edid .= '0';
// Stereo mode 00 = No Stereo
$edid .= '00';
// Sync type 11 = Digital separate
$edid .= '11';
// -VSync
$edid .= '0';
// +HSync
$edid .= '1';
// 2-way line-interleaved stereo, if bits 6-5 are not 00.
$edid .= '0';
// Other Monitor Descriptors
// Required Flag
$edid .= '00000000' . '00000000';
// Reserved Flag
$edid .= '00000000';
// Descriptor Data Type Tag: Monitor Serial Number
$edid .= '11111111';
// Block Flag
$edid .= '00000000';
// Monitor Serial Number (ASCII) terminated by LF and pad with SP if < 13 bytes
// LNZ080024214
$edid .= '01001100' . '01001110' . '01011010'. '';

