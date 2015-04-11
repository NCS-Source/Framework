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
 * A simple template class,
 * inspired from Chad Minick Template engine (http://chadminick.com/articles/simple-php-template-engine.html)
 * 
 * @author Martin "Garth" Zander <garth@new-crusader.de>
 * @license MIT License https://github.com/NCS-Source/Framework/blob/master/LICENSE
 * @package NCS Framework
 */

namespace core
{
    use core\template\Breadcrumb;

    /**
     * Class Template
     * 
     * @subpackage core
     */
    class Template
    {
        /**
         * internal instance of this object
         * 
         * @var self $instance
         */
        private static $instance;
        
        /**
         * template vars
         * 
         * @var array $vars
         */
        private $vars = array();

        /**
         * the next template
         * 
         * @var string $template
         */
        private $template = 'main';

        /**
         * the main template
         *
         * @var string $mainTemplate
         */
        private $mainTemplate = 'main';
        
        /**
         * internal content var
         * 
         * @var string $content
         */
        private $content = '';

        /**
         * @var Breadcrumb $breadcrumb
         */
        private $breadcrumb;

        /**
         * 
         */
        public function __construct()
        {
            $this->breadcrumb = new Breadcrumb();
        }
        
        /**
         * returns a instance of database class
         *
         * @return self
         */
        public static function getInstance()
        {
            if (is_object(self::$instance) == false) {
                self::$instance = new Template();
            }

            return self::$instance;
        }
        
        /**
         * magic function for get template vars
         * 
         * @param string $name
         *
         * @return string
         */
        public function __get($name)
        {
            return $this->vars[$name];
        }

        /**
         * magic method for set template vars
         * 
         * @param string $name
         * @param string|array $tplValue
         */
        public function __set($name, $tplValue)
        {
            if (is_array($tplValue) == true) {
                $htmlSpecialChars = function($value) {
                    return htmlspecialchars($value, ENT_HTML5|ENT_SUBSTITUTE|ENT_DISALLOWED, 'ISO-8859-15');
                };
                $tplValue = array_map($htmlSpecialChars, $tplValue);
            } else {
                $tplValue = htmlspecialchars($tplValue, ENT_HTML5|ENT_SUBSTITUTE|ENT_DISALLOWED, 'ISO-8859-15');
            }
            
            $this->vars[(string)$name] = $tplValue;
        }

        /**
         * a cleanup of template vars
         */
        public function cleanVars()
        {
            $this->vars = array();
        }

        /**
         * set a template
         * 
         * @param string $template
         */
        public function setTemplate($template)
        {
            $this->template = $template;
        }

        /**
         * set a template
         *
         * @param string $template
         */
        public function setMainTemplate($template)
        {
            $this->mainTemplate = $template;
        }
        
        /**
         * render a template
         * 
         * @return string
         * 
         * @throws \Exception
         */
        private function render()
        {
            if (file_exists(\Config::DIR_APP . 'views/' . $this->template . '.tpl.php') == false) {
                throw new \Exception('Template ('. $this->template . ') not exist');
            }
            
            // get all vars
            extract($this->vars, EXTR_PREFIX_ALL, 'tpl_var');
            
            // start output
            ob_start();
            
            include(\Config::DIR_APP . 'views/' . $this->template . '.tpl.php');
            
            return ob_get_clean();
        }

        /**
         * render and returns the template content, without internal saving
         * 
         * @return string
         */
        public function makeContent()
        {
            $content = '';
            
            try {
                $content = $this->render();
            } catch (\Exception $renderException) {
                error_log($renderException->getMessage());
            }
            
            return $content;
        }

        /**
         * render a template and save the content internal
         * 
         * @return bool
         */
        public function createContent()
        {
            $content = $this->makeContent();
            
            if (empty($content) == false) {
                $this->content .= $content;
                
                return true;
            }
            
            return false;
        }

        /**
         * @param Breadcrumb $breadcrumb
         */
        public function setBreadcrumb($breadcrumb)
        {
            $this->breadcrumb = $breadcrumb;
        }

        /**
         * @return Breadcrumb
         */
        public function getBreadcrumb()
        {
            return $this->breadcrumb;
        }
        
        /**
         * display main template with content
         * 
         * @throws \Exception
         */
        public function display()
        {
            if (file_exists(\Config::DIR_APP . 'views/' . $this->mainTemplate . '.tpl.php') == false) {
                throw new \Exception('Template not exist');
            }

            $content = $this->content;

            $breadcrumb = $this->breadcrumb;
            
            // get all vars
            extract($this->vars, EXTR_PREFIX_ALL, 'tpl_var');

            // start output
            ob_start();

            include(\Config::DIR_APP . 'views/' . $this->mainTemplate . '.tpl.php');

            echo ob_get_clean();
        }
    }
}