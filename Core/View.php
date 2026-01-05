<?php

namespace Core;

use Exception;

class View implements Executable
{

    private string $baseViewPath = __DIR__ . '/../App/Views';
    private readonly string $fullViewPath;
    private array $data;

    /**
     * @throws Exception
     */
    private function __construct(string $viewName, array $data = [])
    {
        list($exists, $fullPath) = $this->viewExists($viewName);
        if (!$exists) {
            http_response_code(404);
            throw new Exception(sprintf('File %s not found at specified path %s', $viewName, $fullPath));
        }

        $this->fullViewPath = $fullPath;
        $this->data = $data;
    }


    /**
     * @throws Exception
     */
    public static function make(string $viewName, array $data = []): View
    {
        return new View($viewName, $data);
    }

    /**
     * Determine if a view file exists or not
     * @param string $viewName
     * @return array
     */
    private function viewExists(string $viewName): array
    {
        $viewFullName = sprintf('%s.view.php', $viewName);
        $fullPath = sprintf('%s/%s', $this->baseViewPath, $viewFullName);

        return [file_exists($fullPath), $fullPath];
    }

    #[\Override]
    public function execute()
    {
        extract($this->data);
        return include $this->fullViewPath;
    }
}