<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\PhpRenderer;

require '../composer_modules/autoload.php';

$app = new \Slim\App([ 'settings' => [ 'displayErrorDetails' => true ]]);

$container = $app->getContainer();
$container['renderer'] = new PhpRenderer("../dist");

$app->get('/', function (Request $request, Response $response){
    return $this->renderer->render($response, "/index.html");
});

$app->run();