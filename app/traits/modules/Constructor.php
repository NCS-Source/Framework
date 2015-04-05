<?php
/**
 * @package NCS Framework
 * @author Martin "Garth" Zander <garth@new-crusader.de>
 */

namespace traits\modules
{
    use core\ConstructException;

    /**
     * trait Constructor
     */
    trait Constructor
    {
        /**
         * DB class constructor
         *
         * @param string $module
         *
         * @throws ConstructException
         */
        public function __construct($module)
        {
            if (class_exists($module) == true) {
                try {
                    $this->module = new $module();
                } catch (\Exception $moduleException) {
                    throw new ConstructException($moduleException);
                }
            }
        }
    }
}