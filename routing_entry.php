<?php
/*
 * Dit is een voorbeeld van een index met een router. Extra punten/uitdaging voor studenten.
 *
 * Om deze routing correct te gebruiken je de losse execute command bij elke controller weghalen,
 * anders word elke controller uitgevoerd.
 *
 * Extra extra:
 * Door de .htaccess.example file te hernoemen naar .htaccess worden alle requests naar
 * dit mapje direct naar deze routing file gestuurd, en kan je dus
 * mijnsite.nl/news doen ipv mijnsite.nl/routing_entry.php/news
 * */

include_once __DIR__ . "/routes.php";
global $router;

//Wat gegoochel om het relatieve path achter routing_entry.php te krijgen.
function removeCommonStart($str1, $str2) {
    // Find the length of the common prefix
    $commonLength = strspn($str1 ^ $str2, "\0");

    // Remove the common start from both strings
    $remainingStr1 = substr($str1, $commonLength);
    $remainingStr2 = substr($str2, $commonLength);

    return $remainingStr1;
}
//Plek waar deze file staat
$scriptPath = $_SERVER['SCRIPT_NAME'];
//gevraagde path
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
//Alles achter de / na de standaard route
$route = removeCommonStart($path, $scriptPath);

//lege route vervangen met /
if($route == "") {
    $route = "/";
}
//Zeker maken dat de route begint met een /
if($route[0] != "/"){
    $route = "/" . $route;
}

// Dispatch the route
$router->dispatch($route);