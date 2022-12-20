<?php
if (!function_exists("tasks_parse_db_row")) {
    /**
     * Parse the database rows to task element
     * @param mixed $rows
     * @return array
     */
    function tasks_parse_db_row($rows)
    {
        $tasks = array();
        foreach ($rows as $row) {

            $keyTask = tasks_search_element_in_array($row, $tasks, 'id');
            $task = array();
            if (is_bool($keyTask) && !$keyTask) {
                $task['id'] = isset($row['id']) ? $row['id'] : 0;
                $task['priority'] = isset($row['priority']) ? $row['priority'] : 0;
                $task['assigner'] = isset($row['assigner']) ? $row['assigner'] : "";
                $task['tags'] = isset($row['tags']) ? $row['tags'] : "";
                $task['description'] = isset($row['description']) ? $row['description'] : "";
                $task['due_date'] = isset($row['due_date']) ? $row['due_date'] : 0;
                $task['status'] = isset($row['status']) ? $row['status'] : "";
                if(isset($row['comment_id']))
                    $task['comments'][] = array(
                        'comment_id' => $row['comment_id'],
                        'text' => isset($row['text']) ? $row['text'] : ""
                    );
                else
                    $task['comments'] = array();
                if(isset($row['attachment_id']))
                    $task['attachments'][] = array(
                        'attachment_id' => isset($row['attachment_id']) ? $row['attachment_id'] : "",
                        'filename' => isset($row['filename']) ? $row['filename'] : "",
                        'file' => isset($row['file']) ? $row['file'] : ""
                    );
                else
                    $task['attachments'] = array();

                $tasks[] = $task;
            } else {
                $task = $tasks[$keyTask];

                //Comment element to add
                if(isset($row['comment_id']))
                    $comment = array(
                        'comment_id' => $row['comment_id'],
                        'text' => isset($row['text']) ? $row['text'] : ""
                    );
                //Search if element is in array comments
                $keyComment = tasks_search_element_in_array($row, $task['comments'], 'comment_id');
                if (tasks_success_to_add($row['comment_id'], $keyComment))
                    $task['comments'][] = $comment;

                //Attachment element to add
                if(isset($row['attachment_id']))
                    $attachment = array(
                        'attachment_id' => $row['attachment_id'],
                        'filename' => isset($row['filename']) ? $row['filename'] : "",
                        'file' => isset($row['file']) ? $row['file'] : ""
                    );
                //Search if element is in array attachments
                $keyAttachment = tasks_search_element_in_array($row, $task['attachments'], 'attachment_id');
                if (tasks_success_to_add($row['attachment_id'], $keyAttachment))
                    $task['attachments'][] = $attachment;

                $tasks[$keyTask] = $task;
            }
        }

        return $tasks;
    }
}

if (!function_exists("tasks_parse_request")) {
    /**
     * Parse a request to complete the task element
     * @param mixed $request
     * @return stdClass
     */
    function tasks_parse_request($request)
    {
        $task = new stdClass();
        $task->priority = isset($request->priority) ? $request->priority : 0;
        $task->assigner = isset($request->assigner) ? $request->assigner : "";
        $task->tags = isset($request->tags) ? $request->tags : "";
        $task->description = isset($request->description) ? $request->description : "";
        $task->due_date = isset($request->due_date) ? $request->due_date : 0;
        $task->status = isset($request->status) ? $request->status : "";

        $comments = array();
        foreach ($request->comments as $inpt_comment) {
            $comment = new stdClass();
            $comment->text = isset($inpt_comment->text) ? $inpt_comment->text : "";
            $comments[] = $comment;
        }
        $task->comments = $comments;

        $attachments = array();
        foreach ($request->attachments as $inpt_attachment) {
            $attachment = new stdClass();
            $attachment->filename = isset($inpt_attachment->filename) ? $inpt_attachment->filename : 0;
            $attachment->file = isset($inpt_attachment->file) ? $inpt_attachment->file : "";
            $attachments[] = $attachment;
        }
        $task->attachments = $attachments;

        return $task;
    }
}

if (!function_exists("tasks_get_task")) {
    /**
     * Get task element from request to save in database 
     * @param mixed $task
     * @return stdClass
     */
    function tasks_get_task($task)
    {
        $obj_to_store = new stdClass();
        $obj_to_store->priority = isset($task->priority) ? $task->priority : 0;
        $obj_to_store->assigner = isset($task->assigner) ? $task->assigner : "";
        $obj_to_store->tags = isset($task->tags) ? $task->tags : "";
        $obj_to_store->description = isset($task->description) ? $task->description : "";
        $obj_to_store->due_date = isset($task->due_date) ? $task->due_date : 0;
        $obj_to_store->status = isset($task->status) ? $task->status : "";

        return $obj_to_store;
    }
}

if (!function_exists("tasks_set_task_id")) {
    /**
     * Set the task_id to the element to save in database
     * @param mixed $task
     * @param mixed $task_id
     * @param mixed $type
     * @return mixed
     */
    function tasks_set_task_id($task, $task_id, $type = 'comments')
    {
        $object = null;

        if (isset($task) && property_exists($task, $type)) {
            if (is_array($task->$type)) {
                foreach ($task->$type as $key => $row) {
                    $row->task_id = $task_id;
                }
                $object = $task->$type;
            }
        }

        return $object;
    }
}

if (!function_exists("tasks_status_valid_values")) {
    /**
     * Return the status valid
     * @return array<string>
     */
    function tasks_status_valid_values()
    {
        return [
            "Todo",
            "Doing",
            "Blocked",
            "Done"
        ];
    }
}

if (!function_exists("tasks_status_valid")) {
    /**
     * Validate if the status send is correct
     * @param mixed $status
     * @return bool
     */
    function tasks_status_valid($status)
    {
        $valid = false;
        $error = false;
        if (empty($status))
            $error = true;

        if (!$error && in_array($status, tasks_status_valid_values()))
            $valid = true;

        return $valid;
    }
}

if (!function_exists("tasks_search_element_in_array")) {
    /**
     * Search if element exist in array
     * @param mixed $element
     * @param mixed $arrayElements
     * @param mixed $key
     * @return bool|int|string
     */
    function tasks_search_element_in_array($element, $arrayElements, $key)
    {
        return array_search($element[$key], array_column($arrayElements, $key));;
    }
}

if (!function_exists("tasks_success_to_add")) {
    /**
     * Validate if the status send is correct
     * @param mixed $status
     * @return bool
     */
    function tasks_success_to_add($element, $value)
    {
        return isset($element) && is_bool($value) && !$value ? true : false;
    }
}
