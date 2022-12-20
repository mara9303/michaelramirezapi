<?php

namespace MichaelRamirezApi\Model;
use Exception;

class Attachment extends BaseModel{

    public function __construct(){
        parent::__construct();
        $this->setTable('attachments');
        $this->setColumns("*");
        $this->config->helper("objects");
    }

    /**
     * Get all attachments or one
     * @param mixed $id
     * @return \MichaelRamirezApi\Utils\ReturnResponse
     */
    public function get($id=0){
        try {
            if ($id > 0)
                $this->setWhere(["id" => $id]);

            $this->returnResponse->setData(
                $this->db->select(
                    $this->getTable(), 
                    $this->getColumns(), 
                    $this->getWhere()
                )
            );
            $this->returnResponse->setStatus('success');
        }catch(Exception $ex){
            $this->returnResponse->setMessage("Error: " . $ex->getMessage() . ", code " . $ex->getCode());
            $this->returnResponse->setStatus('error');
        }

        return $this->returnResponse;
    }

    /**
     * Store an attachment
     * @param mixed $data
     * @return \MichaelRamirezApi\Utils\ReturnResponse
     */
    public function store($data = null){
        try {
            
            if(!isset($data)){
                $this->returnResponse->setMessage("Error: the values are empty.");
                $this->returnResponse->setStatus('error');
            }
            
            //Set data post to save
            $this->setColumns(objects_to_array($data));

            if (!$this->returnResponse->error() && !$this->returnResponse->fail()) {
                $this->db->insert($this->getTable(), $this->getColumns());
                $this->returnResponse->setMessage('Attachment saved');
                $this->returnResponse->setStatus('success');
            }
        }catch(Exception $ex){
            $this->returnResponse->setMessage("Error: " . $ex->getMessage() . ", code " . $ex->getCode());
            $this->returnResponse->setStatus('error');
        }

        return $this->returnResponse;
    }

    /**
     * Store multiples attachments
     * @param mixed $data
     * @return \MichaelRamirezApi\Utils\ReturnResponse
     */
    public function store_multiple($data = null){
        try {
            
            if(!isset($data)){
                $this->returnResponse->setMessage("Error: the values are empty.");
                $this->returnResponse->setStatus('error');
            }
            
            //Set data post to save
            $this->setColumns(objects_to_array($data));

            if (!$this->returnResponse->error() && !$this->returnResponse->fail()) {
                $this->db->insert($this->getTable(), $this->getColumns());
                $this->returnResponse->setMessage('Attachments saved');
                $this->returnResponse->setStatus('success');
            }
        }catch(Exception $ex){
            $this->returnResponse->setMessage("Error: " . $ex->getMessage() . ", code " . $ex->getCode());
            $this->returnResponse->setStatus('error');
        }

        return $this->returnResponse;
    }

    /**
     * Delete an attachment
     * @param mixed $id
     * @return \MichaelRamirezApi\Utils\ReturnResponse
     */
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
                    $this->returnResponse->setMessage('Attachment deleted');
                    $this->returnResponse->setStatus('success');
                }else{
                    $this->returnResponse->setMessage('Attachment no exist');
                    $this->returnResponse->setStatus('fail');
                }
            }
        }catch(Exception $ex){
            $this->returnResponse->setMessage("Error: " . $ex->getMessage() . ", code " . $ex->getCode());
            $this->returnResponse->setStatus('error');
        }

        return $this->returnResponse;
    }

    /**
     * Delete all attachments by task
     * @param mixed $task_id
     * @return \MichaelRamirezApi\Utils\ReturnResponse
     */
    public function deleteByTask($task_id=0){
        try {
            if ($task_id > 0)
                $this->setWhere(["task_id" => $task_id]);
            else{
                $this->returnResponse->setMessage("Error: the task_id is required.");
                $this->returnResponse->setStatus('error');
            }

            if (!$this->returnResponse->error() && !$this->returnResponse->fail()) {
                $data = $this->db->delete($this->getTable(), $this->getWhere());
                if ($data->rowCount() > 0) {
                    $this->returnResponse->setMessage('Attachment deleted');
                    $this->returnResponse->setStatus('success');
                }else{
                    $this->returnResponse->setMessage('No attachment deleted');
                    $this->returnResponse->setStatus('success');
                }
            }
        }catch(Exception $ex){
            $this->returnResponse->setMessage("Error: " . $ex->getMessage() . ", code " . $ex->getCode());
            $this->returnResponse->setStatus('error');
        }

        return $this->returnResponse;
    }
}