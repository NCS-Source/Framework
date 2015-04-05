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

namespace core\router
{
    /**
     * Class Route
     * 
     * @subpackage core\router
     */
    class Route
    {
        /**
         * url of this route
         * 
         * @var string $url
         */
        private $url;

        /**
         * controller of this route
         * 
         * @var string $controller
         */
        private $controller;

        /**
         * Accepted HTTP methods for this route
         * 
         * @var string[] $methods
         */
        private $methods = array('GET', 'POST', 'PUT', 'DELETE');

        /**
         * Custom parameter filters for this route
         * 
         * @var array
         */
        private $filters = array();

        /**
         * Array containing parameters passed through request URL
         * @var array
         */
        private $parameters = array();
        
        /**
         * @param string $url
         * @param string $controller
         * @param string[] $methods
         */
        public function __construct($url, $controller, array $methods = array())
        {
            $this->url = $url;
            $this->controller = $controller;
            
            if (empty($methods) == false) {
                $this->methods = $methods;
            }
        }

        /**
         * 
         */
        public function dispatch()
        {
            $action = explode('::', $this->controller);
            
            $controllerClass = 'controller\\' . $action[0];
            
            $instance = new $controllerClass;

            call_user_func_array(array($instance, $action[1]), $this->getParameters());
        }

        /**
         * @return mixed
         */
        public function getRegex()
        {
            return preg_replace_callback("/:(\w+)/", array(&$this, 'substituteFilter'), $this->url);
        }

        /**
         * @param $matches
         *
         * @return string
         */
        private function substituteFilter($matches)
        {
            if (isset($matches[1]) && isset($this->filters[$matches[1]])) {
                return $this->filters[$matches[1]];
            }
            return "([\w-%]+)";
        }
        
        /**
         * @return string
         */
        public function getUrl()
        {
            return $this->url;
        }

        /**
         * @return string[]
         */
        public function getMethods()
        {
            return $this->methods;
        }

        /**
         * @return string
         */
        public function getController()
        {
            return $this->controller;
        }

        /**
         * @param array $parameters
         */
        public function setParameters(array $parameters)
        {
            $this->parameters = $parameters;
        }

        /**
         * @return array
         */
        public function getParameters()
        {
            return $this->parameters;
        }
    }
}