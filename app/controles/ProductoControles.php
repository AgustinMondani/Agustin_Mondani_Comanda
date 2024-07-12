<?php

require_once 'modelos/Producto.php';
require_once 'interfaces/IApiUsable.php';

class ProductoControles extends Producto implements IApiUsable{
    
    public function CargarUno($request, $response, $args)
    {
        $areas = array("bartender", "cervecero", "cocinero");
        $parametros = $request->getParsedBody();

        $nombre = $parametros['nombre'];
        $precio = $parametros['precio'];
        $area_preparacion = $parametros['area_preparacion'];

        if(is_numeric($precio) && floatval($precio) > 0 && in_array($area_preparacion, $areas)){
            if(Producto::existeYActualizar($parametros)){
                $payload = json_encode(array("Exito" => "Prducto existente y actualizado"));
            }
            else{
                $producto = new Producto();
                $producto->nombre = $nombre;
                $producto->precio = (int)$precio;
                $producto->area_preparacion = $area_preparacion;
                $producto->altaProducto();

                $payload = json_encode(array("Exito" => "Producto creado con exito"));
            }
        }
        else{
            $payload = json_encode(array("Error" => "Precio o Area de Preparacion Incorrecta"));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Producto::obtenerTodos();
        $payload = json_encode(array("Carta" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
}