<?php

use Firebase\JWT\JWT;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\PhpRenderer;

require '../composer_modules/autoload.php';

$app = new \Slim\App([ 'settings' => [ 'displayErrorDetails' => true ]]);

// DI Container
$container = $app->getContainer();
$container['renderer'] = new PhpRenderer("../dist");
$container['secret'] = 'ThisIsASuperMegaSecretKey';

// Routes
$app->get('/token', function (Request $request, Response $response) {
    $token = [
        "iss" => "http://localhost",
        "iat" => time(),
        'exp' => strtotime("+12 month")
        // You can add private claims here
    ];
    // Encode payload with the secret key
    $encoded_token = JWT::encode($token, $this->secret);
    return $response->withJson([ "token" => $encoded_token ]);
});

$app->group('/v1/public', function () use ($app) {
    // We want to secure all API calls under /v1/public
    $app->get('/comics[/{id}]', function (Request $request, Response $response) {
        $json_data = file_get_contents('../help/marvel.json');
        $json = json_decode($json_data, true);
        return $response->withJson($json);
    });
})->add(function (Request $request, Response $response, $next) {
    // We define a middleware to protect the access to the current group

    $headers = $request->getHeader("Authorization"); // Check for token in header
    $header = isset($headers[0]) ? $headers[0] : "";

    if (preg_match("/Bearer\s+(.*)$/i", $header, $matches)) {
        $token = $matches[1];
        try {
            // We try to decode the token in header with secret key,
            // if it fails we return a 401 Unauthorized HTTP Response
            $decoded = JWT::decode(
                $token,
                $this->secret,
                ["HS256", "HS512", "HS384"]
            );
            $request = $request->withAttribute("token", $decoded);
            return $next($request, $response);
        } catch (Exception $exception) {
            // Do nothing, we will return a 401 response anyway
        }
    }
    return new Response(401);
});

$app->get('/', function (Request $request, Response $response){
    return $this->renderer->render($response, "/index.html");
});

$app->run();