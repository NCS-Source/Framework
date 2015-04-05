<?php
/**
 * MIT License
 * ===========
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 * -----------------------------------------------------------------------
 *
 * @author Martin "Garth" Zander <garth@new-crusader.de>
 * @license MIT License https://github.com/NCS-Source/Framework/blob/master/LICENSE
 * @package NCS Framework
 */

namespace core\image\modules
{
    use core\image\Interfaces;

    /**
     * Class ImageMagick
     * 
     */
    class ImageMagick extends \Imagick implements Interfaces
    {
        /**
         * width of the new image
         * 
         * @var int $width
         */
        private $width = 50;

        /**
         * height of the new image
         * 
         * @var string $height
         */
        private $height = 50;

        /**
         * the fill color
         * 
         * @var string $fillColor
         */
        private $fillColor = 'white';

        /**
         * @var \Imagick $imageSource
         */
        private $imageSource;
        
        /**
         * save a image to file
         *
         * @param bool $withoutProcess
         * 
         * @return bool
         */
        public function save($withoutProcess = false)
        {
            if (($this->imageSource instanceof \Imagick) == false) {
                error_log(__CLASS__ . __METHOD__ . 'saving image without image source');
                return false;
            }
            
            try {

                if ($withoutProcess == false && $this->processSource() == false) {
                    error_log('can not process image: ' . $this->getfilename());
                    return false;
                } else {
                    $this->resizeImage($this->width, $this->height, \Imagick::FILTER_LANCZOS, 1, true);
                }

                if ($this->writeImage($this->getfilename()) == true) {
                    return true;
                }
                
                error_log('can not write image: ' . $this->getfilename());
                
                return false;
                
            } catch (\Exception $imageException) {
                
                // log error
                error_log(__CLASS__ . __METHOD__ . $imageException->getMessage());
                
                return false;
            }
        }

        /**
         * resize and composite the source file
         * 
         * @return bool
         */
        private function processSource()
        {
            // create new image
            $this->newImage($this->width, $this->height, $this->fillColor);

            // create new source image scale
            $newWidth  = $this->imageSource->getImageWidth();
            $newHeight = $this->imageSource->getImageHeight();
            if ($newHeight > $this->height) {
                $newWidth = ($this->height / $newHeight) * $newWidth;
                $newHeight = $this->height;
            }
            if ($newWidth > $this->width) {
                $newHeight = ($this->width / $newWidth) * $newHeight;
                $newWidth = $this->width;
            }

            // resize the source image
            $this->imageSource->resizeImage($newWidth, $newHeight, \Imagick::FILTER_LANCZOS, 1, true);

            $this->setgravity(\Imagick::GRAVITY_CENTER);
            $this->setImageColorspace($this->imageSource->getImageColorspace());

            // generate offset of source image
            $offsetX = ($this->width - $this->imageSource->getImageWidth()) / 2;
            $offsetY = ($this->height - $this->imageSource->getImageHeight()) / 2;

            // combine the source image with new image mask
            if ($this->compositeImage($this->imageSource, $this->imageSource->getImageCompose(),
                    $offsetX, $offsetY) == false
            ) {
                error_log('error on compositeImage');
                return false;
            }
            $this->flattenImages();
            
            return true;
        }

        /**
         * set the width pixel of image
         *
         * @param int $pixel
         */
        public function setWidth($pixel)
        {
            $this->width = (int)$pixel;
        }

        /**
         * set the height pixel of image
         *
         * @param int $pixel
         */
        public function setHeight($pixel)
        {
            $this->height = (int)$pixel;
        }

        /**
         * set the source image for processing as binary
         *
         * @param string $source
         */
        public function setImageSourceBlob($source)
        {
            $this->imageSource = new \Imagick();
            $this->imageSource->readImageBlob($source);
        }

        /**
         * set a color for the default background of image
         *
         * @param string $color
         */
        public function setFillColor($color)
        {
            $this->fillColor = $color;
        }
    }
}