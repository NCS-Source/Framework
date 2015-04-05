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
    use core\session\Interfaces;
    use traits\modules\Session;

    /**
     * session module class Php
     * 
     * @subpackage core\session
     */
    class Php implements Interfaces
    {
        use Session;

        /**
         * initial class, create, read and start session
         */
        public function __construct()
        {
            // check has request a session Id
            if (array_key_exists($this->name, $_COOKIE)) {
                // get session Id
                $this->sessionId = $_COOKIE[$this->name];
            } else {
                // create a new session Id
                $this->sessionId = $this->createId();
            }

            // enabled standard session functions from PHP
            ini_set('session.use_cookies', true);
            ini_set('session.use_trans_sid', false);

            // set session life time
            ini_set('session.gc_maxlifetime', 24*60*60*1);

            // set session name
            session_name($this->name);
            
            // session starten
            session_id($this->sessionId);
            session_start();

            // set session data to internal var
            $this->data = $_SESSION;
        }


        /**
         * last action before the class killed, save all data
         */
        public function __destruct()
        {
            // close the session with user data
            session_write_close();
        }
    }
}
