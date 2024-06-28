<?php

require_once 'modelos/Producto.php';
require_once 'interfaces/IApiUsable.php';

class ProductoControles extends Producto implements IApiUsable{
    
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $nombre = $parametros['nombre'];
        $precio = $parametros['precio'];
        $area_preparacion = $parametros['area_preparacion'];

        // Creamos el Producto
        $producto = new Producto();
        $producto->nombre = $nombre;
        $producto->precio = (int)$precio;
        $producto->area_preparacion = $area_preparacion;
        $producto->altaProducto();

        $payload = json_encode(array("mensaje" => "Producto creado con exito"));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Producto::obtenerTodos();
        $payload = json_encode(array("listaProductos" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
    public function TraerUno($request, $response, $args)
    {
        $producto_id = (int)$args['id'];
        $producto = Producto::obtenerProducotID($producto_id);
        $payload = json_encode($producto);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}