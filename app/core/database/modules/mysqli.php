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

namespace core\database\modules
{
    use core\Database;
    use core\database\ModuleInterface;

    /**
     * db module Class Mysql
     * @subpackage core\db
     */
    class Mysqli extends \MySqli implements ModuleInterface
    {
        /**
         * @var string $moduleName
         */
        private $moduleName = Database::MODULE_MYSQLI;

        /**
         * @return string
         */
        public function getModuleName()
        {
            return $this->moduleName;
        }

        /**
         * Open a new connection to the MySQL server
         * 
         * @param string $hostname
         * @param string $username
         * @param string $password
         * @param string $database
         * @param int $port
         * @param string $socket
         */
        public function connect($hostname = null, $username = null, $password = null, $database = null,
                                $port = null, $socket = null
        ) {
            parent::connect($hostname, $username, $password, $database, $port, $socket);
        }
        
        /**
         * @todo implement
         * @param string $query
         *
         * @return bool
         */
        public function query($query)
        {
            $res = parent::query($query);
            
            if (empty($res) == true) {
                return false;
            }
            
            return true;
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
            return parent::escape_string($string);
        }

        /**
         * returns the first one result of query
         *
         * @todo implement
         * @param string $query
         *
         * @return string
         */
        public function getOne($query)
        {
            $result = parent::query($query);

            if (($result instanceof \mysqli_result) == false) {
                return '';
            }

            $data = $result->fetch_array(MYSQL_NUM);
            
            if (empty($data) == true) {
                return '';
            }
            
            return $data['0'];
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
            $result = parent::query($query);
            
            if (($result instanceof \mysqli_result) == false) {
                return array();
            }
            
            $data = array();
            
            while ($resultData = $result->fetch_assoc()) {
                if (empty($resultData) == false) {
                    $data[] = $resultData;
                }
            }
            
            return $data;
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
            $result = parent::query($query);

            if (($result instanceof \mysqli_result) == false) {
                return array();
            }

            $data = $result->fetch_assoc();
            
            if (empty($data) == true) {
                return array();
            }

            return $data;
        }
    }
}