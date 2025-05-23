<?php


namespace Request;

use Request\RequestInterface;

class Request implements RequestInterface {
    public function getMethod(): string {
        return $_SERVER['REQUEST_METHOD'];
    }

    // public function getPath(): string {
    //     $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    //     return rtrim($path, '/');
    // }

    public function getPath(): string {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    
        // Adjust for subdirectory
        $basePath = '/mvc-main';
        if (str_starts_with($uri, $basePath)) {
            $uri = substr($uri, strlen($basePath));
        }
    
        // Debug output
        error_log("Parsed path: " . $uri);
    
        return $uri === '' ? '/' : $uri;
    }
    

    public function getBody(): array {
        if ($this->getMethod() === 'POST' || $this->getMethod() === 'PUT') {
            // Check for application/x-www-form-urlencoded (HTML form)
            if (!empty($_POST)) {
                return $_POST;
            }
    
            // Check for raw JSON input
            $rawInput = file_get_contents('php://input');
            $decoded = json_decode($rawInput, true);
    
            if (is_array($decoded)) {
                return $decoded;
            }
        }
    
        return [];
    }
    
}
