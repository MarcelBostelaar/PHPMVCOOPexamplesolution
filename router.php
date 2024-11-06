<?php
// Basic Router Class
class Router
{
    private $routes = [];
    private $notfoundRouteFunc;

    function __construct($notfoundRouteFunc){
        $this->notfoundRouteFunc = $notfoundRouteFunc;
    }

    // Register a route with a handler
    public function addRoute($path, $handler)
    {
        $this->routes[$path] = $handler;
    }

    // Execute the route's handler if found, or show a 404 page
    public function dispatch($requestedPath)
    {
        if (array_key_exists($requestedPath, $this->routes)) {
            $this->routes[$requestedPath]();
        } else {
            http_response_code(404);
            $temp = $this->notfoundRouteFunc;
            $temp();
        }
    }
}

