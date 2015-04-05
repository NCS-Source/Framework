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
    use core\database\Interfaces;

    /**
     * import module namespace as module
     */
    use core\database as module;
    use core\database\ModuleInterface;
    use traits\modules\Constructor;

    /**
     * Class Database
     * @subpackage core
     */
    class Database implements Interfaces
    {
        use Constructor {
            __construct as private;
        }

        /**
         * database connection username
         * 
         * @var string $username
         */
        private $username = null;

        /**
         * database connection password
         * 
         * @var string $password
         */
        private $password = null;

        /**
         * default database database
         * 
         * @var string $database
         */
        private $database = null;

        /**
         * connection hostname to database
         * 
         * @var string $hostname
         */
        private $hostname = null;
        
        /**
         * @var Interfaces[] $instance
         */
        private static $instance;
        
        /**
         * name of MySQL DB Module
         * 
         * @const string MODULE_MYSQLI
         */
        const MODULE_MYSQLI = '\core\database\modules\Mysqli';

        /**
         * name of default database Module
         *
         * @const string MODULE_DEFAULT
         */
        const MODULE_DEFAULT = self::MODULE_MYSQLI;
        
        /**
         * @var ModuleInterface $module
         */
        private $module;

        /**
         * returns a instance of database class
         * 
         * @param string $module
         * 
         * @return self
         */
        public static function getInstance($module = self::MODULE_DEFAULT)
        {
            if (is_object(self::$instance[$module]) == false) {
                self::$instance[$module] = new Database($module);
            }

            return self::$instance[$module];
        }

        /**
         * Open a new connection to the MySQL server
         */
        public function connect()
        {
            $this->module->connect($this->hostname, $this->username, $this->password, $this->database, null, null);
        }
        
        /**
         * @todo 
         * 
         * @param string $query
         *
         * @return bool|object
         */
        public function query($query)
        {
            return $this->module->query($query);
        }

        /**
         * escape a string
         * 
         * @param string $string
         *
         * @return string
         */
        public function escape($string)
        {
            return $this->module->escape($string);
        }

        /**
         * returns the first one result of query
         * 
         * @param string $query
         *
         * @return string
         */
        public function getOne($query)
        {
            return $this->module->getOne($query);
        }

        /**
         * returns a result array of query
         *
         * @todo implement
         * @param string $query
         *
         * @return array
         */
        public function getAll($query)
        {
            return $this->module->getAll($query);
        }

        /**
         * returns a row of query result
         * 
         * @param string $query
         * 
         * @return array
         */
        public function getRow($query)
        {
            return $this->module->getRow($query);
        }
        
        /**
         * @param string $database
         */
        public function setDatabase($database)
        {
            $this->database = $database;
        }

        /**
         * @param string $password
         */
        public function setPassword($password)
        {
            $this->password = $password;
        }

        /**
         * @param string $hostname
         */
        public function setHostname($hostname)
        {
            $this->hostname = $hostname;
        }

        /**
         * @param string $username
         */
        public function setUsername($username)
        {
            $this->username = $username;
        }
    }
}