<?php
namespace MichaelRamirezApi;

use Exception;
use MichaelRamirezApi\Traits\SingletonTrait;
use MichaelRamirezApi\Utils\Config;
use MichaelRamirezApi\Utils\ReturnResponse;
use MichaelRamirezApi\Utils\SQLiteConnection;
use MichaelRamirezApi\Database\DB;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
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

    /** @var ReturnResponse */
    private $returnResponse;

    /** @var SQLiteConnection */
    private $pdo;

    /** @var Medoo */
    private $db;

    private function __construct(){
        try {
            $this->config = Config::create();
            $loader = new PhpFileLoader(new FileLocator());
            $this->routes = $loader->load($this->config->getConfigDir() . '/routes.php');
            $this->request = Request::createFromGlobals();
            $this->context = new RequestContext();
            $this->returnResponse = new ReturnResponse();
            $this->pdo = SQLiteConnection::create();

            /*Load Database Config */
            if (!file_exists($file_path = dirname(__DIR__) . '/config/databases.php')) {
                throw new \ErrorException('The configuration file config/databases.php does not exist.');
            }

            include($file_path);

            $this->db = DB::getConnetion($config['type'], $config['database']);

            //Load helper
            $this->config->helper('permission');
        }catch(Exception $ex){
            $returnResponse = new ReturnResponse();
            $returnResponse->setData(["trace" => $ex->getTraceAsString()]);
            $returnResponse->setMessage($ex->getMessage() . ' - ' . $ex->getCode());
            $returnResponse->setStatus('error');
            
            $response = new Response(
                $returnResponse->getObject(true),
                Response::HTTP_INTERNAL_SERVER_ERROR,
                ['content-type' => 'application/json']
            );
            $response->send();
            die;
        }
    }

    public function getConfig(){
        return $this->config;
    }

    public function getConnection(){
        return $this->pdo;
    }

    public function getMedooConnection(){
        return $this->db;
    }

    public function run(){
        try {
            if(!isset($this->db->pdo)){
                $this->returnResponse->setMessage("Could not connect to the database. Please review the DB_NAME enviroment variable.");
                $this->returnResponse->setStatus('fail');
                $response = new Response(
                    $this->returnResponse->getObject(true),
                    Response::HTTP_INTERNAL_SERVER_ERROR,
                    ['content-type' => 'application/json']
                );
            } else {
                
                if(permission_validate_api_token_exist($this->request->headers->get('apiToken'))){
                    $this->returnResponse->setStatus('success');
                }else{
                    $this->returnResponse->setMessage("Access not allowed: apiToken not found.");
                    $this->returnResponse->setStatus('fail');
                }

                if($this->returnResponse->getStatus() == "success"){
                    //Set Method to context
                    $this->context->setMethod($this->request->server->get('REQUEST_METHOD'));
                    //Create matcher to search routes
                    $matcher = new UrlMatcher($this->routes, $this->context);
                    //Request match else throw ResourceNotFoundException
                    $parameters = $matcher->matchRequest($this->request);
                    //Get controller configurated in routes
                    $controllerClass = $parameters['_controller'][0];
                    //Get method to call
                    $controllerMethod = $parameters['_controller'][1];
                    //Create class of controller
                    $controllerObject = new $controllerClass();
                    
                    unset($parameters['_controller'], $parameters['_route']);
    
                    $this->request->query = new InputBag($parameters);
                    
                    
    
                    call_user_func([$controllerObject, $controllerMethod], $this->request);
                    die;
                }else{
                    $response = new Response(
                        $this->returnResponse->getObject(true),
                        Response::HTTP_NOT_FOUND,
                        ['content-type' => 'application/json']
                    );
                }
                
            }
        } catch (ResourceNotFoundException $ex) {
            //$response = new Response('Not Found', 404);
            $this->returnResponse->setMessage("Not Found");
            $this->returnResponse->setStatus('error');
            $response = new Response(
                $this->returnResponse->getObject(true),
                Response::HTTP_NOT_FOUND,
                ['content-type' => 'application/json']
            );
        } catch (\PDOException $ex) {
            $this->returnResponse->setMessage("Could not connect to the database. Error: " . $ex->getMessage());
            $this->returnResponse->setStatus('error');
            $response = new Response(
                $this->returnResponse->getObject(true),
                Response::HTTP_INTERNAL_SERVER_ERROR,
                ['content-type' => 'application/json']
            );
        } catch (Exception $ex) {
            //$response = new Response('An error occurred', 500);
            $this->returnResponse->setMessage("An error occurred: " . $ex->getMessage());
            $this->returnResponse->setStatus('error');
            $response = new Response(
                $this->returnResponse->getObject(true),
                Response::HTTP_INTERNAL_SERVER_ERROR,
                ['content-type' => 'application/json']
            );
        }  finally {
            $response->send();
        }
    }
}