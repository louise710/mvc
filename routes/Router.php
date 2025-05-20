<?php


namespace Routes;

use Request\RequestInterface;
use Responses\Response;


class Router {
    private $request;
    private $routeMatcher;
    private $routes = [];

    public function __construct(RequestInterface $request, RouteMatcher $routeMatcher) {
        $this->request = $request;
        $this->routeMatcher = $routeMatcher;
    }

    public function addRoute($method, $path, $handler) {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'handler' => $handler
        ];
    }

    public function dispatch() {
        $match = $this->routeMatcher->match(
            $this->routes,
            $this->request->getMethod(),
            $this->request->getPath()
        );
        
        if ($match) {
            return call_user_func_array($match['handler'], array_values($match['params']));
        }

        return new Response(404, json_encode(['error' => 'Not Found']));
    }
}