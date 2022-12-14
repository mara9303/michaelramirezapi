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
    }

    /**
     * Get root directory
     * @return string
     */
    public function getRootDir(){
        return $this->get('ROOT_DIR');
    }

    /**
     * Get config directory
     * @return string
     */
    public function getEnv(){
        return $this->get('APP_ENV');
    }

    /**
     * Get config directory
     * @return string
     */
    public function getConfigDir(){
        return $this->getRootDir().'/config';
    }
}
