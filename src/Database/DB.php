<?php

namespace MichaelRamirezApi\Database;
use Medoo\Medoo;
use PDO;

class DB{
    final public static function getConnetion($type, $database){
        
        $database = new Medoo([
            'type' => $type,
            'database' => $database,
            'error' => PDO::ERRMODE_SILENT
        ]);
        
        return $database;
    }
}