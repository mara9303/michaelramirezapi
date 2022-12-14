<?php
namespace MichaelRamirezApi\Controllers;

use Exception;
use MichaelRamirezApi\Utils\ReturnResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskController{

    private $returnResponse;

    public function __construct(){
        $this->returnResponse = new ReturnResponse();
    }

    /**
     * Get rows
     * @return void
     */
    public function index(Request $request){
        try {
            $task = $request->get('task');
            $data = array(
                1 => "Primero",
                2 => "Segundo",
                3 => "Tercero",
                4 => "Cuarto",
                5 => "Quinto"
            );

            if ($task > 0)
                $data = $data[$task];

            $this->returnResponse->setData(array("task" => $data));
            $this->returnResponse->setStatus('success');

        } catch (Exception $ex) {
            $this->returnResponse->setMessage("Error: " . $ex->getMessage() . ", code " . $ex->getCode());
            $this->returnResponse->setStatus('error');
        }

        if($this->returnResponse->getStatus() == 'error')
            $response = new Response(
                $this->returnResponse->getObject(true),
                Response::HTTP_INTERNAL_SERVER_ERROR,
                ['content-type' => 'application/json']
            );
        else
            $response = new Response(
                $this->returnResponse->getObject(true),
                Response::HTTP_OK,
                ['content-type' => 'application/json']
            );
        
        $response->send();
    }

    public function store(Request $request){
        $data = array(1,2,3,4,5);
       
        $response = new Response(
            json_encode($data),
            Response::HTTP_OK,
            ['content-type' => 'application/json']
        );
        
        $response->send();
    }

    public function update(Request $request){
        $data = array(1,2,3,4,5);
       
        $response = new Response(
            json_encode($data),
            Response::HTTP_OK,
            ['content-type' => 'application/json']
        );
        
        $response->send();
    }

    public function delete($task){
        $data = array(1,2,3,4,5);
       
        $response = new Response(
            json_encode($data),
            Response::HTTP_OK,
            ['content-type' => 'application/json']
        );
        
        $response->send();
    }
}