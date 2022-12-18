<?php
namespace MichaelRamirezApi\Utils;

use MichaelRamirezApi\Traits\RegistryTrait;
use MichaelRamirezApi\Traits\SingletonTrait;
use Symfony\Component\Dotenv\Dotenv;

class Config
{
    use RegistryTrait, SingletonTrait;

    /**
     * Load configurations app
     */
    private function __construct()
    {
        $this->add('ROOT_DIR', dirname(dirname(__DIR__)));
        $env = new Dotenv();
        $env->loadEnv($this->getRootDir() . '/.env');
        foreach($_ENV as $key => $value){
            $this->add($key, $value);
        }
    }

    /**
     * Get root directory
     * @return string
     */
    public function getRootDir(){
        return $this->get('ROOT_DIR');
    }

    /**
     * Get app enviroment
     * @return string
     */
    public function getAppEnv(){
        return $this->get('APP_ENV');
    }

    /**
     * Get all .env variables
     * @return string
     */
    public function env($key){
        return $this->get($key);
    }

    /**
     * Get config directory
     * @return string
     */
    public function getConfigDir(){
        return $this->getRootDir().'/config';
    }

    /**
     * Get data base path
     * @return string
     */
    public function getDataBasePath(){
        return $_ENV['PATH_TO_SQLITE_FILE'];
    }

    /**
     * Get data base path
     * @return string
     */
    public function helper($name){
        return include dirname(__DIR__)."/Helpers/${name}_helper.php";
    }
}
