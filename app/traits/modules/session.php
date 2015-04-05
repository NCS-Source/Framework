<?php
/**
 * @package NCS Framework
 * @author Martin "Garth" Zander <garth@new-crusader.de>
 */

namespace traits\modules
{
    /**
     * trait class Session
     * 
     * @subpackage traits\modules
     */
    trait Session
    {
        /**
         * session name
         * 
         * @var string $name
         */
        private $name = 'sid';

        /**
         * session Id
         * 
         * @var string $id
         */
        private $id;
        
        /**
         * internal session data
         * 
         * @var array $data
         */
        private $data = array();
    
        /**
         * create a session Id
         *
         * @return string
         */
        private function createId()
        {
            return sha1(time() . rand(2298, time()) . md5(microtime() . $this->name . rand(4868, time())) . $this->name);
        }
    }
}
