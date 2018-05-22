<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\PhpRenderer;

require '../composer_modules/autoload.php';

$app = new \Slim\App([ 'settings' => [ 'displayErrorDetails' => true ]]);

$container = $app->getContainer();
$container['renderer'] = new PhpRenderer("../dist");

$app->get('/v1/public/comics[/{id}]', function (Request $request, Response $response) {
    $json_data = file_get_contents('../help/marvel.json');
    $json = json_decode($json_data, true);
    // id parameter is ignored (not useful for JWT demo)
    return $response->withJson($json);
});

$app->get('/', function (Request $request, Response $response){
    return $this->renderer->render($response, "/index.html");
});

$app->run();