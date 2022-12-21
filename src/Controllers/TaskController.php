<?php

namespace MichaelRamirezApi\Controllers;

use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends BaseController
{

    /**
     * Get tasks
     * @return void
     */
    public function index(Request $request)
    {
        try {
            if (
                permission_validate_api_token_can(
                    ["READ", "ALL"], 
                    $request->headers->get('apiToken')
                )
            ) {

                $task = (int)$request->get('task');
                $data = $this->model->get($task);

                if ($data->success()) {
                    $this->returnResponse->setData(array("tasks" => $data->getData()));
                    $this->returnResponse->setStatus($data->getStatus());
                } else {
                    $this->returnResponse->setMessage($data->getMessage());
                    $this->returnResponse->setStatus($data->getStatus());
                }
            }
        } catch (Exception $ex) {
            $this->returnResponse->setMessage("Error: " . $ex->getMessage() . ", code " . $ex->getCode());
            $this->returnResponse->setStatus('error');
        }

        if ($this->returnResponse->error())
            $response = new Response(
                $this->returnResponse->getObject(true),
                Response::HTTP_INTERNAL_SERVER_ERROR,
                ['content-type' => 'application/json']
            );
        elseif ($this->returnResponse->fail())
            $response = new Response(
                $this->returnResponse->getObject(true),
                Response::HTTP_BAD_REQUEST,
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

    /**
     * Store tasks
     * @return void
     */
    public function store(Request $request)
    {
        try {
            if (
                permission_validate_api_token_can(
                    ["WRITE", "ALL"],
                    $request->headers->get('apiToken')
                )
            ) {
                $this->config->helper("tasks");
                $task = tasks_parse_request(json_decode($request->getContent()));

                $data = $this->model->store($task);

                $this->returnResponse->setStatus($data->getStatus());
                $this->returnResponse->setMessage($data->getMessage());
            }
        } catch (Exception $ex) {
            $this->returnResponse->setMessage("Error: " . $ex->getMessage() . ", code " . $ex->getCode());
            $this->returnResponse->setStatus('error');
        }

        if ($this->returnResponse->error())
            $response = new Response(
                $this->returnResponse->getObject(true),
                Response::HTTP_INTERNAL_SERVER_ERROR,
                ['content-type' => 'application/json']
            );
        elseif ($this->returnResponse->fail())
            $response = new Response(
                $this->returnResponse->getObject(true),
                Response::HTTP_BAD_REQUEST,
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

    /**
     * Update tasks
     * @return void
     */
    public function update(Request $request)
    {
        try {
            if (
                permission_validate_api_token_can(
                    ["WRITE", "ALL"],
                    $request->headers->get('apiToken')
                )
            ) {
                $this->config->helper("tasks");
                $task = tasks_parse_request(json_decode($request->getContent()));

                $taskId = (int) $request->get('task');
                $data = $this->model->update($taskId, $task);

                $this->returnResponse->setStatus($data->getStatus());
                $this->returnResponse->setMessage($data->getMessage());
            }
        } catch (Exception $ex) {
            $this->returnResponse->setMessage("Error: " . $ex->getMessage() . ", code " . $ex->getCode());
            $this->returnResponse->setStatus('error');
        }

        if ($this->returnResponse->error())
            $response = new Response(
                $this->returnResponse->getObject(true),
                Response::HTTP_INTERNAL_SERVER_ERROR,
                ['content-type' => 'application/json']
            );
        elseif ($this->returnResponse->fail())
            $response = new Response(
                $this->returnResponse->getObject(true),
                Response::HTTP_BAD_REQUEST,
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

    public function delete(Request $request)
    {
        try {
            if (
                permission_validate_api_token_can(
                    ["WRITE", "ALL"],
                    $request->headers->get('apiToken')
                )
            ) {
                $task = (int) $request->get('task');
                $data = $this->model->delete($task);

                $this->returnResponse->setStatus($data->getStatus());
                $this->returnResponse->setMessage($data->getMessage());
            }
        } catch (Exception $ex) {
            $this->returnResponse->setMessage("Error: " . $ex->getMessage() . ", code " . $ex->getCode());
            $this->returnResponse->setStatus('error');
        }

        if ($this->returnResponse->error())
            $response = new Response(
                $this->returnResponse->getObject(true),
                Response::HTTP_INTERNAL_SERVER_ERROR,
                ['content-type' => 'application/json']
            );
        elseif ($this->returnResponse->fail())
            $response = new Response(
                $this->returnResponse->getObject(true),
                Response::HTTP_BAD_REQUEST,
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
}
