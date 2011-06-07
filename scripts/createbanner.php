<?php
/**
 * XNova Legacies
 *
 * @license http://www.xnova-ng.org/license-legacies
 * @see http://www.xnova-ng.org/
 *
 * Copyright (c) 2009-Present, XNova Support Team
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 *  - Redistributions of source code must retain the above copyright notice,
 * this list of conditions and the following disclaimer.
 *  - Redistributions in binary form must reproduce the above copyright notice,
 * this list of conditions and the following disclaimer in the documentation
 * and/or other materials provided with the distribution.
 *  - Neither the name of the team or any contributor may be used to endorse or
 * promote products derived from this software without specific prior written
 * permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 *
 *                                --> NOTICE <--
 *  This file is part of the core development branch, changing its contents will
 * make you unable to use the automatic updates manager. Please refer to the
 * documentation for further information about customizing XNova.
 *
 */

define('FONT_FILE', dirname(__FILE__) . '/resources/fonts/visitor/visitor.ttf');
define('BACKGROUND_FILE', dirname(__FILE__) . '/resources/backgrounds/default.png');
define('TIMEZONE', 'Europe/Paris');

date_default_timezone_set(TIMEZONE);

require_once dirname(dirname(__FILE__)) . '/db/mysql.php';

/**
 *
 * @todo Update the position alignment management.
 * @author Greg
 *
 */
class Legacies_Block_Image_Png
{
    private $_format = NULL;
    private $_mime = NULL;

    const COLOR_RED   = 'red';
    const COLOR_GREEN = 'green';
    const COLOR_BLUE  = 'blue';

    const POSITION_LEFT    = 0x01;
    const POSITION_RIGHT   = 0x02;
    const POSITION_CENTER  = 0x03;

    const POSITION_TOP     = 0x10;
    const POSITION_BOTTOM  = 0x20;
    const POSITION_MIDDLE  = 0x30;

    const POSITION_DEFAULT = 0x11;

    private $_texts = array();
    private $_background = NULL;
    private $_font = NULL;

    public function __construct()
    {
        $this->_setMime('image/png');
        $this->_setFormat('png');
    }

    protected function _getFormat()
    {
        return $this->_format;
    }

    protected function _setFormat($format)
    {
        $this->_format = $format;

        return $this;
    }

    protected function _getMime()
    {
        return $this->_mime;
    }

    protected function _setMime($mime)
    {
        $this->_mime = $mime;

        return $this;
    }

    public function getHeaders()
    {
        return array(
            'Content-Type' => $this->_getMime()
            );
    }

    public function addText($content, $fontSize, $fontFile, $positionAbscix, $positionOrdinates, $angle, Array $color, $alignment = self::POSITION_DEFAULT)
    {
        $this->_texts[] = array(
            'content' => $content,
            'color' => $color,
            'sizes' => $this->_getTextPositions($content, $fontSize, $fontFile, $positionAbscix, $positionOrdinates, $angle, $alignment),
            'font_size' => $fontSize,
            'font_file' => $fontFile,
            );

        return $this;
    }

    public function _getTextPositions($textContent, $fontSize, $fontFile, $positionAbscix, $positionOrdinates, $angle = 0, $alignment = self::POSITION_DEFAULT)
    {
        $dimensions = imageFtbBox($fontSize, $angle, $fontFile, $textContent);
        $width = $dimensions[2] - $dimensions[0];
        $height = $dimensions[1] - $dimensions[7];

        switch ($alignment & 0x7) {
        case self::POSITION_CENTER:
            $positionAbscix -= ceil($width / 2);
            break;

        case self::POSITION_RIGHT:
            $positionAbscix -= $width;
            break;

        case self::POSITION_LEFT:
        default:
            break;
        }

        switch ($alignment & 0x70) {
        case self::POSITION_MIDDLE:
            $positionOrdinates -= ceil($height / 2);
            break;

        case self::POSITION_TOP:
            $positionOrdinates -= $height;
            break;

        case self::POSITION_BOTTOM:
        default:
            $width = 0;
            $height = 0;
            break;
        }


        return array(
            'abscix' => $positionAbscix,
            'ordinate' => $positionOrdinates,
            'alignment' => $alignment,
            'width' => $width,
            'height' => $height,
            'angle' => $angle
            );
    }

    public function setBackground($backgroundImage)
    {
        $this->_background = $backgroundImage;

        return $this;
    }

    public function setFont($fontFile)
    {
        $this->_font = $fontFile;

        return $this;
    }

    public function render()
    {
        ob_start();
        if (!is_null($this->_background)) {
            $gdHandler = imageCreateFromPng($this->_background);
        } else {
            $gdHandler = imageCreateTrueColor();
        }

        foreach ($this->_texts as $text) {
            $color = imageColorAllocate(
                $gdHandler,
                $text['color'][self::COLOR_RED],
                $text['color'][self::COLOR_GREEN],
                $text['color'][self::COLOR_BLUE]
                );

            switch ($text['sizes']['alignment'] & 0x0F) {
            case self::POSITION_CENTER:
                $text['sizes']['abscix'] -= floor(imageSX($gdHandler) / 2);
                break;

            case self::POSITION_RIGHT:
                $text['sizes']['abscix'] = imageSX($gdHandler) - ($text['sizes']['abscix'] + $text['sizes']['width']) -10;
                break;

            case self::POSITION_LEFT:
            default:
                break;
            }

            $imageText = imageTtfText(
                $gdHandler,
                $text['font_size'],
                $text['sizes']['angle'],
                $text['sizes']['abscix'],
                $text['sizes']['ordinate'],
                $color,
                $text['font_file'],
                $text['content']
                );
        }

        imagePng($gdHandler);
        imageDestroy($gdHandler);

        $imageData = ob_get_contents();
        ob_end_clean();

        return $imageData;
    }
}

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
} else {
    header('HTTP/1.1 412 Precondition Failed');
    die();
}

$textColor = array(
    Legacies_Block_Image_Png::COLOR_RED   => 0xEF,
    Legacies_Block_Image_Png::COLOR_GREEN => 0xEF,
    Legacies_Block_Image_Png::COLOR_BLUE  => 0xEF
    );

$sql = <<<EOF
SELECT
  users.username AS username,
  planets.name AS planet_name,
  stats.build_points AS build_points,
  stats.fleet_points AS fleet_points,
  stats.tech_points AS tech_points,
  stats.total_points AS total_points
FROM {{table}}users AS users
LEFT JOIN {{table}}statpoints AS stats ON stats.id_owner=users.id
LEFT JOIN {{table}}planets AS planets ON planets.id_owner=users.id
WHERE users.id={$id}
EOF;


$data = array_merge(
    array(
        'game_name' => 'XNova:Legacies',
        'date' => date('d M Y')
        ),
    doquery($sql, '', true)
    );

function number_format_custom($number)
{
    return number_format($number, 0, ',', '.');
}

$image = new Legacies_Block_Image_Png();
$image
    ->setBackground(BACKGROUND_FILE)
    ->addText($data['game_name'], 24, FONT_FILE, 5, 37, 0, $textColor)
    ->addText($data['username'], 24, FONT_FILE, 250, 37, 0, $textColor)

    ->addText('Batiments:',
        14, FONT_FILE, 5, 50, 0, $textColor)
    ->addText(number_format_custom($data['build_points']), 14, FONT_FILE, 100, 50, 0, $textColor)
    ->addText('Flottes:', 14, FONT_FILE, 5, 70, 0, $textColor)
    ->addText(number_format_custom($data['fleet_points']), 14, FONT_FILE, 100, 70, 0, $textColor)

    ->addText('Technologies:', 14, FONT_FILE, 205, 50, 0, $textColor)
    ->addText(number_format_custom($data['tech_points']), 14, FONT_FILE, 320, 50, 0, $textColor)
    ->addText('Total:', 14, FONT_FILE, 205, 70, 0, $textColor)
    ->addText(number_format_custom($data['total_points']), 14, FONT_FILE, 320, 70, 0, $textColor)
;

foreach ($image->getHeaders() as $headerName => $headerValue) {
    header(sprintf('%s: %s', $headerName, $headerValue));
}

echo $image->render();
