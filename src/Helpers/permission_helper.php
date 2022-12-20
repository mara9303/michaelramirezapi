<?php
use MichaelRamirezApi\App;
use MichaelRamirezApi\Exceptions\PermissionException;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;

if(!function_exists("permission_validate_api_token_exist")){
    /**
     * Validate api token exist
     * @param mixed $obj
     * @return mixed
     */
    function permission_validate_api_token_exist($apiToken){
        return isset($apiToken) && !empty($apiToken) ? true : throw new InvalidConfigurationException('The API_TOKEN is not present');
    }
}

if(!function_exists("permission_validate_api_token_can")){
    /**
     * Return validation if apiToken can make an action
     * @param mixed $obj
     * @return mixed
     */
    function permission_validate_api_token_can($permissions, $apiToken){
        $result = true;
        foreach($permissions as $permission){
            if(!in_array(strtoupper($permission), permission_get_by_token($apiToken))){
                $result = false;
            }else{
                $result = true;
                break;
            }
        }
        if(!$result)
            throw new PermissionException('You don\'t have permission to do this action', 405);
        return $result;
    }
}

if(!function_exists("permission_get_by_token")){
    /**
     * Return the permissions by token
     * @param mixed $obj
     * @return mixed
     */
    function permission_get_by_token($value){
        $permissions = array();
        $app = App::create();
        $config = $app->getConfig();
        if($value == $config->env('API_TOKEN'))
            $permissions = array('READ', 'WRITE', 'ALL');
        elseif($value == $config->env('API_TOKEN_READ'))
            $permissions = array('READ');
        else
            throw new InvalidConfigurationException('The API_TOKEN is not valid');
        return $permissions;
    }
}