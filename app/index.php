<?php 

//  php -S localhost:666 -t app  levantar conexion 
// composer require vlucas/phpdotenv instalar el .env

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';
require_once './controles/UsuarioControles.php';
require_once './base_datos/AccesoDatos.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();


$app = AppFactory::create();


$app->get('/', function ($request, $response, array $args) {
		$response->getBody()->write("Funciona!");
return $response;
});


$app->group('/usuario', function (RouteCollectorProxy $group) {
    $group->get('[/]', \UsuarioControles::class . ':TraerTodos');
    $group->post('[/]', \UsuarioControles::class . ':CargarUno');
  });

$app->run();
?>