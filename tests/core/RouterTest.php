<?php
/**
 * @package NCS Framework
 * @author Martin "Garth" Zander <garth@new-crusader.de>
 */

namespace tests\core
{
    use core\Router;
    use core\router\Collection;
    use core\router\Route;
    use PHPUnit_Framework_TestCase;

    class RouterTest extends PHPUnit_Framework_TestCase
    {
        /**
         * @dataProvider matcherProvider
         */
        public function testMatch(Router $router, $path, $expected)
        {
            $this->assertEquals($expected, (bool) $router->match($path));
        }
    
        /**
         * @return Router
         */
        private function getRouter()
        {
            $collection = new Collection();
            
            $collection->attach(new Route('/samples/', array(
                'controller' => 'tests\SampleController::sample1',
                'methods' => 'GET'
            )));
            
            $collection->attach(new Route('/sample/:id', array(
                'controller' => 'tests\SampleController::sample2',
                'methods' => 'GET'
            )));
            
            $collection->attach(new Route('/', array(
                'controller' => 'tests\SampleController::sample3',
                'methods' => 'GET'
            )));
            
            return new Router($collection);
        }
    
        /**
         * @return array
         */
        public function matcherProvider()
        {
            $router = $this->getRouter();
    
            return array(
                array($router, '', true),
                array($router, '/', true),
                array($router, '/aaa', false),
                array($router, '/samples', true),
                array($router, '/sample/1', true),
                array($router, '/sample/%E3%81%82', true),
            );
        }
    }
}