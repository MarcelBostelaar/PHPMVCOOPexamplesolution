<?php
//Een pagina waar je routes kan definieren. Zie de router.php voor meer info.
include_once __DIR__ . "/router.php";
include_once __DIR__ . "/controllers/homepage.php";
include_once __DIR__ . "/controllers/newspage.php";

// Initialize Router
$router = new Router(
    //Wat je ziet als de route niet bestaat. Kan je vervangen met een echte view
    function () {
        echo "404 route niet gevonden! :)";
    }
);

// Define routes
// Omdat we met controllers als classes hebben gewerkt, kunnen we dit makkelijk doen!
$router->addRoute('/', function () {
    HomepageController::execute();
});

//2 routes naar de home!
$router->addRoute('/home', function () {
    HomepageController::execute();
});

$router->addRoute('/news', function () {
    NewsPageController::execute();
});