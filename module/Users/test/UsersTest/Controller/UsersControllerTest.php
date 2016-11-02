<?php


namespace UsersTest\Controller;


use UsersTest\Bootstrap;
use Zend\Router\Http\TreeRouteStack as HttpRouter;
use Application\Controller\IndexController;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;
use Zend\Router\RouteMatch;
use PHPUnit_Framework_TestCase;

class UsersControllerTest extends \PHPUnit_Framework_TestCase
{
    protected $controller;
    protected $request;
    protected $response;
    protected $routeMatch;
    protected $event;

    public function setUp()
    {
//        $this->setApplicationConfig(
//            include 'application.config.php'
//        );
//        parent::setUp();

        /****************
         *
         */
        $serviceManager = Bootstrap::getServiceManager();
        $this->controller = new IndexController();
        $this->request    = new Request();
        $this->routeMatch = new RouteMatch(array('controller' => 'index'));
        $this->event      = new MvcEvent();
        $config = $serviceManager->get('Config');
        $routerConfig = isset($config['router']) ? $config['router'] : array();
        $router = HttpRouter::factory($routerConfig);

        $this->event->setRouter($router);
        $this->event->setRouteMatch($this->routeMatch);
        $this->controller->setEvent($this->event);
        $this->controller->setServiceLocator($serviceManager);
    }

//    public function testAuthAction()
//    {
//        $this->dispatch('/auth');
//        $this->assertResponseStatusCode(200);
//
////        $this->assertModuleName('Users');
////        $this->assertControllerName(UsersController::class);
////        $this->assertControllerClass('UsersController');
////        $this->assertMatchedRouteName('auth');
//    }

    public function testAuthAction()
    {
        $this->routeMatch->setParam('action', 'auth');

        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
    }
}
