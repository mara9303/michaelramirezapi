<?php

namespace MichaelRamirezApi\Model;
use Exception;

class Task extends BaseModel{

    public function __construct(){
        parent::__construct();
        $this->setTable('tasks');
        $this->setColumns("*");
    }

    public function get($id=0){
        try {
            if ($id > 0)
                $this->setWhere(["id" => $id]);

            $this->returnResponse->setData($this->db->select($this->getTable(), $this->getColumns(), $this->getWhere()));
            $this->returnResponse->setStatus('success');
        }catch(Exception $ex){
            $this->returnResponse->setMessage("Error: " . $ex->getMessage() . ", code " . $ex->getCode());
            $this->returnResponse->setStatus('error');
        }

        return $this->returnResponse;
    }

    public function store($data = null){
        try {
            $this->config->helper("tasks");
            $this->config->helper("objects");
            
            if(!isset($data)){
                $this->returnResponse->setMessage("Error: the values are empty.");
                $this->returnResponse->setStatus('error');
            }
            if(!tasks_status_valid($data->status)){
                $this->returnResponse->setMessage("Error: the status is invalid.");
                $this->returnResponse->setStatus('error');
            }
            //Set data post to save
            $this->setColumns(objects_to_array($data));

            if (!$this->returnResponse->error() && !$this->returnResponse->fail()) {
                $this->db->insert($this->getTable(), $this->getColumns());
                $this->returnResponse->setMessage('Task saved');
                $this->returnResponse->setStatus('success');
            }
        }catch(Exception $ex){
            $this->returnResponse->setMessage("Error: " . $ex->getMessage() . ", code " . $ex->getCode());
            $this->returnResponse->setStatus('error');
        }

        return $this->returnResponse;
    }
    public function update($taskId, $data = null){
        try {
            $this->config->helper("tasks");
            $this->config->helper("objects");
            
            if(!isset($data)){
                $this->returnResponse->setMessage("Error: the values are empty.");
                $this->returnResponse->setStatus('error');
            }
            if(!tasks_status_valid($data->status)){
                $this->returnResponse->setMessage("Error: the status is invalid.");
                $this->returnResponse->setStatus('error');
            }
            $updateData = objects_to_array($data);
            //dump([$taskId, $updateData]);
            //die;
            //Set data post to save
            $this->setWhere(["id" => $taskId]);
            $this->setColumns(objects_to_array($data));

            if (!$this->returnResponse->error() && !$this->returnResponse->fail()) {
                $this->db->update($this->getTable(), $this->getColumns(), $this->getWhere());
                $this->returnResponse->setMessage('Task updated');
                $this->returnResponse->setStatus('success');
            }
        }catch(Exception $ex){
            $this->returnResponse->setMessage("Error: " . $ex->getMessage() . ", code " . $ex->getCode());
            $this->returnResponse->setStatus('error');
        }

        return $this->returnResponse;
    }

    public function delete($id=0){
        try {
            if ($id > 0)
                $this->setWhere(["id" => $id]);
            else{
                $this->returnResponse->setMessage("Error: the id is required.");
                $this->returnResponse->setStatus('error');
            }

            if (!$this->returnResponse->error() && !$this->returnResponse->fail()) {
                $data = $this->db->delete($this->getTable(), $this->getWhere());
                if ($data->rowCount() > 0) {
                    $this->returnResponse->setMessage('Task deleted');
                    $this->returnResponse->setStatus('success');
                }else{
                    $this->returnResponse->setMessage('Task no exist');
                    $this->returnResponse->setStatus('fail');
                }
            }
        }catch(Exception $ex){
            $this->returnResponse->setMessage("Error: " . $ex->getMessage() . ", code " . $ex->getCode());
            $this->returnResponse->setStatus('error');
        }

        return $this->returnResponse;
    }
}