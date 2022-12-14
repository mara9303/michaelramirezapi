<?php
namespace MichaelRamirezApi;

use MichaelRamirezApi\Traits\SingletonTrait;
use MichaelRamirezApi\Utils\Config;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Loader\PhpFileLoader;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

class App{
    use SingletonTrait;

    /** @var Config */
    private $config;

    /** @var RouteCollection */
    private $routes;

    /** @var Request */
    private $request;

    /** @var RequestContext */
    private $context;

    private function __construct(){
        $this->config = Config::create();
        $loader = new PhpFileLoader(new FileLocator());
        $this->routes = $loader->load($this->config->getConfigDir() . '/routes.php');
        $this->request = Request::createFromGlobals();
        $this->context = new RequestContext();
    }

    public function getConfig(){
        return $this->config;
    }

    public function run(){
        
        $matcher = new UrlMatcher($this->routes, $this->context);
        $parameters = $matcher->matchRequest($this->request);
        $controllerClass = $parameters['_controller'][0];
        $controllerObject = new $controllerClass();
        $controllerMethod = $parameters['_controller'][1];

        unset($parameters['_controller'], $parameters['_route']);

        $this->request->query = new InputBag($parameters);

        call_user_func([$controllerObject, $controllerMethod], $this->request);
    }
}