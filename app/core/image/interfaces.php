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

namespace core\image
{
    /**
     * interface Interfaces for image modules
     * 
     * @subpackage core\image
     */
    interface Interfaces
    {
        /**
         * save a image to file
         *
         * @param bool $withoutProcess
         *
         * @return bool
         */
        public function save($withoutProcess = false);

        /**
         * set a new filename for the image
         * 
         * @param string $name
         */
        public function setFilename($name);

        /**
         * set the width pixel of image
         * 
         * @param int $pixel
         */
        public function setWidth($pixel);
        
        /**
         * set the height pixel of image
         *
         * @param int $pixel
         */
        public function setHeight($pixel);

        /**
         * set the source image for processing as binary
         * 
         * @param string $source
         */
        public function setImageSourceBlob($source);

        /**
         * set a color for the default background of image
         * 
         * @param string $color
         */
        public function setFillColor($color);
        
        /**
         * Sets the format of the new created image
         * 
         * @param string $format
         */
        public function setFormat($format);
    }
}
