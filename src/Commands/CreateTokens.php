<?php

namespace MichaelRamirezApi\Commands;

use Exception;
use MichaelRamirezApi\Utils\Config;
use MichaelRamirezApi\Utils\SQLiteConnection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateTokens extends Command
{
    /**
     * The name of the command (the part after "bin/demo").
     *
     * @var string
     */
    protected static $defaultName = 'create:tokens';

    /**
     * The command description shown when running "php bin/demo list".
     *
     * @var string
     */
    protected static $defaultDescription = 'Create tokens to the application';

    /**
     * Execute the command
     *
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @return int 0 if everything went fine, or an exit code.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $config = Config::create();
        $pdo = SQLiteConnection::create();
        $io = new SymfonyStyle($input, $output);
        try {
            $envFile = file_get_contents($config->getRootDir() . '/.env');
            $envFile = str_replace("API_TOKEN='", "API_TOKEN='" . md5(microtime()), $envFile);
            $envFile = str_replace("API_TOKEN_READ='", "API_TOKEN_READ='" . md5(microtime()), $envFile);
            file_put_contents($config->getRootDir() . '/.env', $envFile);
            
            $io->success('Tokens generated!');
        }catch(Exception $ex){
            $io->error(sprintf('Error: %s', $ex->getMessage()));
        }finally{
            //Close DB connection 
            $pdo->close();
        }

        return Command::SUCCESS;
    }
}