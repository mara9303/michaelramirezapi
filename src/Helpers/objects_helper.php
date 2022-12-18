<?php
if(!function_exists("objects_to_array")){
    function objects_to_array($obj){
        return json_decode(json_encode($obj), true);
    }
}

