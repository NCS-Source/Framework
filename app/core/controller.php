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

namespace core
{
    use controller\Index;
    use core\template\Crumb;

    /**
     * Class Controller
     * @subpackage core
     */
    class Controller
    {
        /**
         * @var float $microTimeStart
         */
        private $microTimeStart;
        
        public function __construct()
        {
            $this->getExecutionTime();
            
            /**
             * create session object
             */
            $cSession = Session::getInstance();
            
            /**
             * create database object
             * 
             * @var Database $cDatabase
             */
            $cDatabase = Database::getInstance();
            
            $cDatabase->setUsername(\Config::DB_USERNAME);
            $cDatabase->setPassword(\Config::DB_PASSWORD);
            $cDatabase->setDatabase(\Config::DB_DATABASE);
            
            $cDatabase->connect();
            
            // create router and start content controller
            $cRouter = Router::getInstance();
            
            if ($cRouter->run() == false) {
                // no route found, use default controller
                $cIndex = new Index();
                $cIndex->index();
            }
        }
    
        /**
         * create the HTML output of the site
         */
        public function output()
        {
            $view = Template::getInstance();

            // add first breadcrumb note
            $cCrumb = new Crumb();
            $cCrumb->setTitle('Home');
            $cCrumb->setUrl('/');
            
            $view->getBreadcrumb()->addCrumb($cCrumb, true);
            
            $view->title = 'IT Works!';
            $view->execute_time = $this->getExecutionTime();
            
            $view->display();
        }
        
        /**
         * get execution time in seconds at current point of call in seconds
         * 
         * @return float Execution time at this point of call
         */
        private function getExecutionTime()
        {
            list($microSec, $sec) = explode(' ', microtime());

            if ($this->microTimeStart === null) {
                $this->microTimeStart = ((float)$microSec + (float)$sec);

                return false;
            } else {
                $microTimeStop = ((float)$microSec + (float)$sec);
            }

            return number_format((float)$microTimeStop - (float)$this->microTimeStart, 4, ',', '');
        }
    }
}