<?php
namespace MichaelRamirezApi\Controllers;
use MichaelRamirezApi\App;
use MichaelRamirezApi\Utils\ReturnResponse;

class BaseController {

    /**  @var Config */
    protected $config;

    /**  @var DB */
    protected $db;

    /**  @var ReturnResponse */
    protected $returnResponse;

    /** @var mixed */
    protected $model;

    public function __construct()
    {
        $namespaceArray = explode("\\", get_class($this));
        $model = str_replace("Controller", "", $namespaceArray[(count($namespaceArray) - 1)]);

        $app = App::create();
        
        $namespaceModel = dirname(__NAMESPACE__)."\Model\\$model";

        $this->model = new $namespaceModel();
        $this->config = $app->getConfig();
        $this->db = $app->getConnection2();
        $this->returnResponse = new ReturnResponse();

        $this->config->helper('permission');
    }
}