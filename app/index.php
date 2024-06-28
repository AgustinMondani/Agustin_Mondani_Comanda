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
require_once './controles/ProductoControles.php';
require_once './controles/OrdenControles.php';
require_once './controles/VentaControles.php';
require_once './controles/MesaControles.php';
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

$app->group('/producto', function (RouteCollectorProxy $group) {
  $group->get('[/]', \ProductoControles::class . ':TraerTodos');
  $group->post('[/]', \ProductoControles::class . ':CargarUno');
  //$group->get('/{id}', \ProductoControles::class . ':TraerUno');
});

$app->group('/orden', function (RouteCollectorProxy $group) {
  $group->get('[/]', \OrdenControles::class . ':TraerTodos');
  $group->post('[/]', \OrdenControles::class . ':CargarUno');
});

$app->group('/mesa', function (RouteCollectorProxy $group) {
  $group->get('[/]', \MesaControles::class . ':TraerTodos');
  $group->post('[/]', \MesaControles::class . ':CargarUno');
});

$app->group('/ventas', function (RouteCollectorProxy $group) {
  $group->get('[/]', \VentaControles::class . ':TraerTodos');
});

$app->run();
?>