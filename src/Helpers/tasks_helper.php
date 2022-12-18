<?php
if(!function_exists("tasks_parse_request")){
    function tasks_parse_request($request){
        $task = new stdClass();
        $task->priority = isset($request->priority ) ? $request->priority : 0;
        $task->assigner = isset($request->assigner ) ? $request->assigner :"";
        $task->tags = isset($request->tags) ? $request->tags : "";
        $task->description = isset($request->description) ? $request->description : "";
        $task->due_date = isset($request->due_date) ? $request->due_date : 0;
        $task->status = isset($request->status) ? $request->status : "";
        return $task;
    }
}

if(!function_exists("tasks_status_valid_values")){
    function tasks_status_valid_values(){   
        return [
            "Todo",
            "Doing",
            "Blocked",
            "Done"
        ];
    }
}

if(!function_exists("tasks_status_valid")){
    function tasks_status_valid($status){
        $valid = false;
        $error = false;
        if (empty($status))
            $error = true;

        if (!$error && in_array($status, tasks_status_valid_values()))
            $valid = true;

        return $valid;
    }
}
