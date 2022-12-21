<?php

namespace MichaelRamirezApi\Commands;

use Exception;
use MichaelRamirezApi\Utils\Config;
use MichaelRamirezApi\Utils\SQLiteConnection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateTables extends Command
{
    /**
     * The name of the command (the part after "bin/demo").
     *
     * @var string
     */
    protected static $defaultName = 'create:tables';

    /**
     * The command description shown when running "php bin/demo list".
     *
     * @var string
     */
    protected static $defaultDescription = 'Create tables to the application';

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
        $dbConnetion = $pdo->connect($config->getDataBasePath());
        $io = new SymfonyStyle($input, $output);
        $commands = ['CREATE TABLE IF NOT EXISTS "tasks" (
                        [id] INTEGER PRIMARY KEY AUTOINCREMENT,
                        [priority] INTEGER NOT NULL,
                        [assigner] VARCHAR(255) NOT NULL, 
                        [tags] VARCHAR(255) NOT NULL, 
                        [description] TEXT NOT NULL, 
                        [due_date] DATE NOT NULL, 
                        [status] VARCHAR(255) NOT NULL 
                    );',
                    'CREATE TABLE IF NOT EXISTS "comments" (
                        [id] INTEGER PRIMARY KEY AUTOINCREMENT,
                        [task_id] INTEGER NOT NULL,
                        [text] TEXT NOT NULL,
                        FOREIGN KEY (task_id) REFERENCES tasks(id)
                    );',
                    'CREATE TABLE IF NOT EXISTS "attachments" (
                        [id] INTEGER PRIMARY KEY AUTOINCREMENT,
                        [task_id] INTEGER NOT NULL,
                        [filename] VARCHAR(255) NOT NULL, 
                        [file] BLOB NOT NULL, 
                        FOREIGN KEY(task_id) REFERENCES tasks(id)
                      );'];
        try {
            // execute the sql commands to create new tables
            foreach ($commands as $command) {
                $res = $dbConnetion->exec($command);
                if ($res === false) {
                    $io->error(" Not able to create table ");
                    $io->error($dbConnetion->errorInfo());
                    break;
                }
            }
            $io->success('Well done!');
        }catch(Exception $ex){
            $io->error(sprintf('Error: %s', $ex->getMessage()));
        }finally{
            //Close DB connection 
            $pdo->close();
        }

        return Command::SUCCESS;
    }
}