<?php

namespace Request;

use Request\RequestInterface;

class Request implements RequestInterface {
    public function getMethod(): string {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function getPath(): string {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $basePath = '/mvc-main';
        if (strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }
        return $uri === '' ? '/' : $uri;
    }

    public function getBody(): array {
        $method = $this->getMethod();

        if ($method === 'GET') {
            return $_GET;
        }

        if ($method === 'POST' || $method === 'PUT') {
            if (!empty($_POST)) {
                return $_POST;
            }

            $rawInput = file_get_contents('php://input');
            $decoded = json_decode($rawInput, true);

            if (is_array($decoded)) {
                return $decoded;
            }
        }

        return [];
    }

    public function getHeader(string $key): ?string {
        $headers = getallheaders();
        return $headers[$key] ?? null;
    }

    public function getUri(): string {
        return $_SERVER['REQUEST_URI'];
    }
}
