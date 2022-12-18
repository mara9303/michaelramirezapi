<?php
namespace MichaelRamirezApi\Utils;
use MichaelRamirezApi\Traits\SingletonTrait;

class SQLiteConnection {

    use SingletonTrait;

    /**
     * PDO instance
     * @var type 
     */
    private $pdo;

    private function __construct(){}

    /**
     * return in instance of the PDO object that connects to the database
     * @return \PDO
     */
    public function connect($path = ''){
        if ($this->pdo == null && !empty($path)){
            try {
                $this->pdo = new \PDO("sqlite:" . $path);
             } catch (\PDOException $ex) {
                throw $ex;
             }
        }
        return $this->pdo;
    }

    /**
     * Set null pdo attribute, closing the connection to DB
     * @return \PDO
     */
    public function close(): void{
        if ($this->pdo != null){
            $this->pdo == null;
        }
    }
}
