<?php

/**
 *
 * @todo Update the position alignment management.
 * @author Greg
 *
 */
class Wootook_Core_Model_Image_Png
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