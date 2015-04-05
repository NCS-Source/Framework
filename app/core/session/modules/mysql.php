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

namespace core\session\modules
{
    use core\Database;
    use core\session\Interfaces;
    use traits\modules\Session;

    /**
     * session module class Php
     * 
     * @subpackage core\session
     */
    class Mysql implements Interfaces
    {
        use Session;

        /**
         * initial class, create, read and start session 
         */
        public function __construct()
        {
            // check has request a session Id
            if (array_key_exists($this->name, $_COOKIE) == true) {
                // get session Id
                $this->id = $_COOKIE[$this->name];

                // read session data
                $this->read();

            } else {
                // create a new session Id
                $this->id = $this->createId();
            }

            // disabled standard session functions from PHP
            ini_set('session.use_cookies', false);
            ini_set('session.use_trans_sid', false);

            // set session name
            session_name($this->name);
            
            // start session
            session_id($this->id);
            session_start();

            // save session Id on cookie
            setcookie($this->name, $this->id, time()+3600, null, null, null, 1);

            // set session data to session super var
            $_SESSION = $this->data;
        }

        /**
         * last action before the class killed, save all data
         */
        public function __destruct()
        {
            // get session data from session super var
            $this->data = $_SESSION;

            // save session data
            $this->write();
        }

        /**
         * save session data to database
         */
        private function write()
        {
            $cDatabase = Database::getInstance();

            $data = base64_encode(serialize($this->data));
            
            $qry = 'INSERT INTO session
                    SET session_id = "' . $cDatabase->escape($this->id) . '",
                        session_data = "' . $cDatabase->escape($data) . '",
                        session_time = NOW()
                    ON DUPLICATE KEY UPDATE
                        session_data = VALUES(session_data),
                        session_time = VALUES(session_time)';
            $cDatabase->query($qry);
        }
        
        /**
         * read session data from database
         */
        private function read()
        {
            $cDatabase = Database::getInstance();

            $qry = 'SELECT session_data
                    FROM session
                    WHERE session_id = "' . $cDatabase->escape($this->id) . '"';
            $result = $cDatabase->getOne($qry);

            if (empty($result) == false) {
                $this->data = base64_decode(unserialize($result));
            }
        }
    }
}