<?php

namespace MichaelRamirezApi\Database;
use Medoo\Medoo;
use PDO;

class DB{
    /**
     * Return Medoo Connection
     * @param mixed $type
     * @param mixed $database
     * @return Medoo
     */
    final public static function getConnetion($type, $database){
        
        if(!isset($database) || empty($database)){
            throw new \InvalidArgumentException("The database enviroment variable is not define.");
        }
        $database = new Medoo([
            'type' => $type,
            'database' => $database,
            'error' => PDO::ERRMODE_SILENT
        ]);
        
        return $database;
    }
}