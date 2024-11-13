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
            //Zet response code op 404, wat aangeeft dat de pagina niet gevonden is, wordt gebruikt door apis/browser/libraries
            http_response_code(404);
            //Haal opgeslagen functie op, en voer hem daarna uit.
            $temp = $this->notfoundRouteFunc;
            $temp();
        }
    }
}

