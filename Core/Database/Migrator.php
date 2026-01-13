<?php

namespace Core\Database;

class Migrator
{

    private string $migrationFolder;

    public function __construct()
    {
        // load folder
        $this->migrationFolder = __DIR__ . '/Migrations';
        if(!is_dir($this->migrationFolder)){
            throw new \Exception("Directory not found: $this->migrationFolder");
        }
    }

    /**
     * Run the migrator
     * @return void
     * @throws \Exception
     */
    public function run(): void
    {
        $db = Database::getDBInstance();
        $files = scandir($this->migrationFolder);
        if(is_array($files)){

            // remove the . and .. folders
            $files = array_filter($files, fn($f) => ($f !== '.' && $f !== '..'));

            sort($files);

            echo "Starting migrations\n";
            echo "-----------------------\n";
            foreach ($files as $file) {
                $start = microtime(true);
                $migration = require $this->migrationFolder .'/'.$file;
                $className = $this->getClassNameFromFile($file);
                echo "Running $className ....\n";
                try{
                    $migration->down();
                    $migration->up();
                    $end = microtime(true) - $start;
                    $end = number_format($end, 2);
                    echo "$className migration successful - execution time: {$end}s\n";
                    echo PHP_EOL;
                }catch (\Exception $e){
                    echo "Migration failed for $className\n";
                    echo $e->getMessage();
                }
            }
            echo "Migration finished.";
        }else{
            throw new \Exception("Migration files not found");
        }
    }

    private function getClassNameFromFile(string $file): string
    {
        list($className) = explode('.', $file);
        return $className;
    }

}