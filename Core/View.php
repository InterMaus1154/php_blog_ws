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
        list($exists, $fullPath) = $this->buildViewPath($viewName);
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

    private function buildViewPath(string $viewName): array
    {
        // convert {prefix}.{view} to {Prefix}/{view}
        $parts = explode('.', $viewName);
        $actualViewName = array_pop($parts);

        // uppercase the view prefixes
        $parts = array_map('ucfirst', $parts);

        // join back the full name
        $prefixedViewName = implode('/', $parts) . "/{$actualViewName}";

        $viewFullName = sprintf('%s.view.php', $prefixedViewName);
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