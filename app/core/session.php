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
    /**
     * import the interface
     */
    use core\session\Interfaces;

    /**
     * import module namespace as module
     */
    use core\session as module;
    use traits\modules\Constructor;

    /**
     * Class Session
     * @subpackage core
     */
    class Session implements Interfaces
    {
        use Constructor {
            __construct as private;
        }

        /**
         * @var array[module\Interfaces] $instance
         */
        private static $instance;

        /**
         * name of php standard session Module
         *
         * @const string MODULE_PHP
         */
        const MODULE_PHP = '\core\session\modules\Php';

        /**
         * name of MySQL based session Module
         *
         * @const string MODULE_MYSQL
         */
        const MODULE_MYSQL = '\core\session\modules\Mysql';

        /**
         * name of default session Module
         *
         * @const string MODULE_DEFAULT
         */
        const MODULE_DEFAULT = self::MODULE_PHP;

        /**
         * @var Interfaces $module
         */
        private $module;

        /**
         * returns a instance of session class
         *
         * @param string $module
         *
         * @return self
         */
        public static function getInstance($module = self::MODULE_DEFAULT)
        {
            if (is_object(self::$instance[$module]) == false) {
                self::$instance[$module] = new Session($module);
            }

            return self::$instance[$module];
        }
    }
}