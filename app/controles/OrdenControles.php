<?php

require_once 'modelos/Orden.php';
require_once 'modelos/Venta.php';
require_once 'interfaces/IApiUsable.php';

class OrdenControles extends Orden implements IApiUsable{

    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        

        $codigo = $parametros['codigo'];
        $id_mesa = $parametros['id_mesa'];
        $productos = $parametros['productos'];
        $cliente_nombre = $parametros['cliente_nombre'];

        //midelware de validacion de #existencia de productos
        //asignacion de tareas a las comandas
        $productos = json_decode($productos, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($productos)) {
            // Procesar cada producto
            foreach ($productos as $producto) {
                $venta = new Venta();
                $venta->producto = $producto["producto"];
                $venta->codigo = $codigo;
                $venta->cantidad = (int)$producto["cantidad"];
                $venta->estado = "espera";
                $venta->id_usuario = null;
                $venta->altaVenta();
            }
        } else {
            // Manejar el error en caso de que la decodificación falle
            $payload = json_encode(array("error" => "Formato de productos inválido"));
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    
        $orden = new Orden();
        $orden->codigo = $codigo;
        $orden->id_mesa = (int)$id_mesa;
        $orden->id_mozo = 0; #"asignar el id del mozo que toma el pedido";
        $orden->estado = "espera"; // hardcodeado para probar la db
        $orden->hora_pedido = date('H:i:s');
        $orden->cliente_nombre = $cliente_nombre;
        $orden->altaOrden();

        $payload = json_encode(array("mensaje" => "Orden creado con exito"));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Orden::obtenerTodos();
        $payload = json_encode(array("listaOrdenes" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}