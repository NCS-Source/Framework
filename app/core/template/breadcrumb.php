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

namespace core\template
{
    use models\helper\Iterator;
    use Traversable;

    /**
     * Class Breadcrumb
     * 
     * @subpackage core\template
     */
    class Breadcrumb implements \IteratorAggregate
    {
        /**
         * @var Crumb[] $crumbs
         */
        private $crumbs = array();

        /**
         * @param Crumb $crumb
         * @param bool $first
         */
        public function addCrumb(Crumb $crumb, $first = false)
        {
            if ($first == true) {
                array_unshift($this->crumbs, $crumb);
            } else {
                $this->crumbs[] = $crumb;
            }
        }

        /**
         * @return bool
         */
        public function hasCrumbs()
        {
            if (empty($this->crumbs) == false) {
                return true;
            }

            return false;
        }
        
        /**
         * (PHP 5 &gt;= 5.0.0)<br/>
         * Retrieve an external iterator
         * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
         * @return Traversable An instance of an object implementing <b>Iterator</b> or
         * <b>Traversable</b>
         */
        public function getIterator()
        {
            return new Iterator($this->crumbs);
        }
    }
}
