<?php

namespace MichaelRamirezApi\Model;

use Exception;
use MichaelRamirezApi\Model\Comment;

class Task extends BaseModel
{

    public function __construct()
    {
        parent::__construct();
        $this->setTable('tasks');
        $this->setColumns("*");
        $this->config->helper("tasks");
        $this->config->helper("objects");
    }

    /**
     * Get all comments or one
     * @param mixed $id
     * @return \MichaelRamirezApi\Utils\ReturnResponse
     */
    public function get($id = 0)
    {
        try {
            if ($id > 0)
                $this->setWhere(["tasks.id" => $id]);

            //Set tables in join
            $this->setJoins([
                "[>]comments (c)" => ["tasks.id" => "task_id"],
                "[>]attachments (a)" => ["tasks.id" => "task_id"]
            ]);
            //Set columns to select
            $this->setColumns(
                [
                    "tasks.id",
                    "tasks.priority",
                    "tasks.assigner",
                    "tasks.tags",
                    "tasks.description",
                    "tasks.due_date",
                    "tasks.status",
                    "c.id (comment_id)",
                    "c.text",
                    "a.id (attachment_id)",
                    "a.filename",
                    "a.file"
                ]
            );

            $this->returnResponse->setData(
                tasks_parse_db_row(
                    $this->db->select(
                        $this->getTable(),
                        $this->getJoins(),
                        $this->getColumns(),
                        $this->getWhere()
                    )
                )
            );

            $this->returnResponse->setStatus('success');
        } catch (Exception $ex) {
            $this->returnResponse->setMessage("Error: " . $ex->getMessage() . ", code " . $ex->getCode());
            $this->returnResponse->setStatus('error');
        }

        return $this->returnResponse;
    }

    /**
     * Store a comment
     * @param mixed $data
     * @return \MichaelRamirezApi\Utils\ReturnResponse
     */
    public function store($data = null)
    {
        try {

            if (!isset($data)) {
                $this->returnResponse->setMessage("Error: the values are empty.");
                $this->returnResponse->setStatus('error');
            }
            if (!tasks_status_valid($data->status)) {
                $this->returnResponse->setMessage("Error: the status is invalid.");
                $this->returnResponse->setStatus('error');
            }
            //Set task data post to save
            $this->setColumns(objects_to_array(tasks_get_task($data)));

            if (!$this->returnResponse->error() && !$this->returnResponse->fail()) {
                //Store Task
                $this->db->insert($this->getTable(), $this->getColumns());

                //Store comments
                $commentClass = new Comment();
                $task_id = $this->db->id();
                $returnComment = $commentClass->store_multiple(
                    tasks_set_task_id($data, $task_id)
                );

                if ($returnComment->success()) {
                    //Store comments
                    $attachmentClass = new Attachment();
                    $store = tasks_set_task_id($data, $task_id, 'attachments');
                    $returnAttachment = $attachmentClass->store_multiple($store);
                    if ($returnAttachment->success()) {
                        $this->returnResponse->setMessage('Task saved');
                        $this->returnResponse->setStatus('success');
                    } else {
                        $this->returnResponse->setMessage($returnAttachment->getMessage());
                        $this->returnResponse->setStatus($returnAttachment->getStatus());
                    }
                } else {
                    $this->returnResponse->setMessage($returnComment->getMessage());
                    $this->returnResponse->setStatus($returnComment->getStatus());
                }
            }
        } catch (Exception $ex) {
            $this->returnResponse->setMessage("Error: " . $ex->getMessage() . ", code " . $ex->getCode());
            $this->returnResponse->setStatus('error');
        }

        return $this->returnResponse;
    }

    /**
     * Update a comment
     * @param mixed $data
     * @return \MichaelRamirezApi\Utils\ReturnResponse
     */
    public function update($taskId, $data = null)
    {
        try {

            if (!isset($data)) {
                $this->returnResponse->setMessage("Error: the values are empty.");
                $this->returnResponse->setStatus('error');
            }
            if (!tasks_status_valid($data->status)) {
                $this->returnResponse->setMessage("Error: the status is invalid.");
                $this->returnResponse->setStatus('error');
            }

            $this->setWhere(["id" => $taskId]);
            //Set task data post to save
            $this->setColumns(objects_to_array(tasks_get_task($data)));

            if (!$this->returnResponse->error() && !$this->returnResponse->fail()) {
                //Update Task
                $this->db->update($this->getTable(), $this->getColumns(), $this->getWhere());
                //Store comments
                $commentClass = new Comment();
                //Delete all comments in the task
                $returnDeletedComment = $commentClass->deleteByTask($taskId);
                if ($returnDeletedComment->success()) {
                    //Store multiple comments
                    $returnComment = $commentClass->store_multiple(
                        tasks_set_task_id($data, $taskId)
                    );

                    if ($returnComment->success()) {
                        $attachmentClass = new Attachment();
                        //Delete all attachments in the task
                        $returnDeletedAttachment = $attachmentClass->deleteByTask($taskId);
                        if ($returnDeletedAttachment->success()) {
                            //Store multiple attachments
                            $returnAttachment = $attachmentClass->store_multiple(
                                tasks_set_task_id($data, $taskId, 'attachments')
                            );
                            if ($returnAttachment->success()) {
                                $this->returnResponse->setMessage('Task updated');
                                $this->returnResponse->setStatus('success');
                            } else {
                                $this->returnResponse->setMessage($returnAttachment->getMessage());
                                $this->returnResponse->setStatus($returnAttachment->getStatus());
                            }
                        } else {
                            $this->returnResponse->setMessage($returnDeletedAttachment->getMessage());
                            $this->returnResponse->setStatus($returnDeletedAttachment->getStatus());
                        }
                    } else {
                        $this->returnResponse->setMessage($returnComment->getMessage());
                        $this->returnResponse->setStatus($returnComment->getStatus());
                    }
                } else {
                    $this->returnResponse->setMessage($returnDeletedComment->getMessage());
                    $this->returnResponse->setStatus($returnDeletedComment->getStatus());
                }
            }
        } catch (Exception $ex) {
            $this->returnResponse->setMessage("Error: " . $ex->getMessage() . ", code " . $ex->getCode());
            $this->returnResponse->setStatus('error');
        }

        return $this->returnResponse;
    }

    /**
     * Delete a task
     * @param mixed $id
     * @return \MichaelRamirezApi\Utils\ReturnResponse
     */
    public function delete($id = 0)
    {
        try {
            if ($id > 0)
                $this->setWhere(["id" => $id]);
            else {
                $this->returnResponse->setMessage("Error: the id is required.");
                $this->returnResponse->setStatus('error');
            }

            $commentClass = new Comment();
            //Delete all comments in the task
            $returnDeletedComment = $commentClass->deleteByTask($id);
            if (!$returnDeletedComment->success()) {
                $this->returnResponse->setMessage($returnDeletedComment->getMessage());
                $this->returnResponse->setStatus($returnDeletedComment->getStatus());
            }

            $attachmentClass = new Attachment();
            //Delete all attachments in the task
            $returnDeletedAttachment = $attachmentClass->deleteByTask($id);
            if (!$returnDeletedAttachment->success()) {
                $this->returnResponse->setMessage($returnDeletedAttachment->getMessage());
                $this->returnResponse->setStatus($returnDeletedAttachment->getStatus());
            }

            if (!$this->returnResponse->error() && !$this->returnResponse->fail()) {
                $data = $this->db->delete($this->getTable(), $this->getWhere());
                if ($data->rowCount() > 0) {
                    $this->returnResponse->setMessage('Task deleted');
                    $this->returnResponse->setStatus('success');
                } else {
                    $this->returnResponse->setMessage('Task no exist');
                    $this->returnResponse->setStatus('fail');
                }
            }
        } catch (Exception $ex) {
            $this->returnResponse->setMessage("Error: " . $ex->getMessage() . ", code " . $ex->getCode());
            $this->returnResponse->setStatus('error');
        }

        return $this->returnResponse;
    }
}
