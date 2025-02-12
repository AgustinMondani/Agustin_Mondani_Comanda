<?php 

//  php -S localhost:666 -t app  levantar conexion 
// composer require vlucas/phpdotenv instalar el .env
// composer require firebase/php-jwt

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require_once './requires.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();


$app = AppFactory::create();

$app->addErrorMiddleware(true, true, true);

$app->group('/login', function (RouteCollectorProxy $group) {
  $group->post('[/]', \JwtControles::class . ':TokenLogin');
});

$app->group('/usuario', function (RouteCollectorProxy $group) {
    $group->get('[/]', \UsuarioControles::class . ':TraerTodos');
    $group->post('[/]', \UsuarioControles::class . ':CargarUno');
    $group->put('/modificar', \UsuarioControles::class . ':ModificarUno');
  })->add(new AccesoMiddleware(["socio"]));

$app->group('/producto', function (RouteCollectorProxy $group) {
  $group->get('[/]', \ProductoControles::class . ':TraerTodos');
  $group->post('[/]', \ProductoControles::class . ':CargarUno')->add(new AccesoMiddleware(["socio", "cocinero"]));
});

$app->group('/orden', function (RouteCollectorProxy $group) {
  $group->get('[/]', \OrdenControles::class . ':TraerTodos')->add(new AccesoMiddleware(["socio", "mozo"]));
  $group->post('[/]', \OrdenControles::class . ':CargarUno')->add(new AccesoMiddleware(["mozo"]));
  $group->post('/imagen', \OrdenControles::class . ':AgregarImagen')->add(new AccesoMiddleware(["mozo"]));
  $group->get('/demora', \OrdenControles::class . ':DemoraOrden');
  $group->get('/lista', \OrdenControles::class . ':ListaOrdenes')->add(new AccesoMiddleware(["socio", "mozo"]));
  $group->put('/servir', \OrdenControles::class . ':ServirOrden')->add(new AccesoMiddleware(["mozo"]));
  $group->put('/cobrar', \OrdenControles::class . ':CobrarOrden')->add(new AccesoMiddleware(["mozo"]));
  $group->get('/tarde', \OrdenControles::class . ':OrdenesEntregadasTarde')->add(new AccesoMiddleware(["socio"]));
  $group->get('/aTiempo', \OrdenControles::class . ':OrdenesEntregadasATiempo')->add(new AccesoMiddleware(["socio"]));
});

$app->group('/ventas', function (RouteCollectorProxy $group) {
  $group->get('[/]', \VentaControles::class . ':TraerTodos')->add(new AccesoMiddleware(["socio"]));
  $group->get('/rol', \VentaControles::class . ':TraerVentasRol')->add(new RolMiddleware());
});

$app->group('/mesa', function(RouteCollectorProxy $group){
  $group->post('[/]', \MesaControles::class . ':CargarUno')->add(new AccesoMiddleware(["socio"]));
  $group->put('/modificar', \MesaControles::class . ':ModificarUno')->add(new RolMiddleware());
  $group->get('[/]', \MesaControles::class . ':TraerTodos')->add(new AccesoMiddleware(["socio", "mozo"]));
  $group->get('/masUsada', \MesaControles::class . ':MasUsada')->add(new AccesoMiddleware(["socio"]));
});

$app->group('/preparar', function (RouteCollectorProxy $group) {
  $group->put('[/]', \UsuarioControles::class . ':ComezarAPreparar')->add(new RolMiddleware());
  $group->put('/finalizar', \UsuarioControles::class . ':FinalizarPreparacion')->add(new RolMiddleware());
});

$app->group('/descargar', function (RouteCollectorProxy $group) {
  $group->get('[/]', function ($request, $response, $args){
    return descargarCSV($request, $response, $args);
  })->add(new AccesoMiddleware(["socio"]));
});

$app->group('/encuesta', function (RouteCollectorProxy $group) {
  $group->post('[/]', \EncuestaControles::class . ':CargarUno');
  $group->get('/mejores', \EncuestaControles::class . ':MejoresEncuestas')->add(new AccesoMiddleware(["socio"]));
});

$app->run();
?>