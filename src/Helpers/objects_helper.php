<?php
if(!function_exists("objects_to_array")){
    /**
     * Return array from object
     * @param mixed $obj
     * @return mixed
     */
    function objects_to_array($obj){
        return json_decode(json_encode($obj), true);
    }
}

