<?php

require_once 'modelos/Venta.php';
require_once 'interfaces/IApiUsable.php';

class VentaControles extends Venta implements IApiUsable{
    
    public function CargarUno($request, $response, $args){

    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Venta::obtenerSegunRol("cervecero");
        $payload = json_encode(array("listaVentas" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}