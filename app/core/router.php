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
 * A simple router class, inspired from PHP-Router (https://github.com/dannyvankooten/PHP-Router)
 * 
 * @author Martin "Garth" Zander <garth@new-crusader.de>
 * @license MIT License https://github.com/NCS-Source/Framework/blob/master/LICENSE
 * @package NCS Framework
 */

namespace core
{
    use core\router\Collection;
    use core\router\Route;

    /**
     * Class Router
     * 
     * @subpackage core
     */
    class Router
    {
        /**
         * a instance of self
         * 
         * @var self $instance
         */
        private static $instance;

        /**
         * Array that holds all Route objects
         * 
         * @var Collection
         */
        private $routes = array();

        /**
         * current route object
         * 
         * @var Route $route
         */
        private $route;
        
        /**
         * @param Collection $collection
         */
        public function __construct(Collection $collection)
        {
            $this->routes = $collection;
        }
        
        /**
         * returns a instance of database class
         *
         * @return self
         */
        public static function getInstance()
        {
            if (is_object(self::$instance) == false) {
                self::$instance = self::parseConfig(\Config::loadRouterConfig());
            }

            return self::$instance;
        }

        /**
         * Create routes by array, and return a Router object
         *
         * @param array $config
         *
         * @return self
         */
        private static function parseConfig(array $config)
        {
            $cCollection = new Collection();
            
            if (empty($config['routes']) == false) {
                foreach ($config['routes'] as $route) {
                    $methods = array();
                    if (empty($route[2]) == false) {
                        if (is_array($route['2']) == false) {
                            $methods = array($route['2']);
                        } else {
                            $methods = $route['2'];
                        }
                    }
                    
                    $cCollection->attach(new Route($route['0'], $route['1'], $methods));
                }
            }
            
            $cRouter = new Router($cCollection);
            
            return $cRouter;
        }
        
        /**
         * Matches the current request against mapped routes
         */
        public function run()
        {
            $requestMethod = $_SERVER['REQUEST_METHOD'];
            
            $requestUrl = $_SERVER['REQUEST_URI'];
            
            // strip GET variables from URL
            if (($pos = strpos($requestUrl, '?')) !== false) {
                $requestUrl = substr($requestUrl, 0, $pos);
            }
            
            if ($this->match($requestUrl, $requestMethod) == true) {
                $this->route->dispatch();
                
                return true;
            }
            
            return false;
        }

        /**
         * Match given request _url and request method and see if a route has been defined for it
         * If so, return route's target
         * If called multiple times
         *
         * @param string $requestUrl
         * @param string $requestMethod
         * 
         * @return bool
         */
        public function match($requestUrl, $requestMethod = 'GET')
        {
            /**
             * @var Route $cRoute
             */
            foreach ($this->routes->all() as $cRoute) {
                // compare server request method with route's allowed http methods
                if (in_array($requestMethod, (array)$cRoute->getMethods()) == false) {
                    continue;
                }
                
                // check if request _url matches route regex. if not, return false.
                if (preg_match("@^" . $cRoute->getRegex() . "*$@i", $requestUrl, $matches) == false) {
                    continue;
                }
                
                $params = array();
                
                if (preg_match_all("/:([\w-%]+)/", $cRoute->getUrl(), $argument_keys)) {
                    // grab array with matches
                    $argument_keys = $argument_keys[1];
                    
                    // loop trough parameter names, store matching value in $params array
                    foreach ($argument_keys as $key => $name) {
                        if (isset($matches[$key + 1])) {
                            $params[$name] = $matches[$key + 1];
                        }
                    }
                }

                $cRoute->setParameters($params);
                
                $this->route = $cRoute;
                
                return true;
            }
            
            return false;
        }
    }
}