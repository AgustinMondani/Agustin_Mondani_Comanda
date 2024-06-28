<?php

require_once 'modelos/Mesa.php';
require_once 'interfaces/IApiUsable.php';

class MesaControles extends Mesa implements IApiUsable{
    
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $estado = $parametros['estado'];
        $numero = $parametros['numero'];

        // Creamos la Mesa
        $mesa = new Mesa();
        $mesa->estado = $estado;
        $mesa->numero = (int)$numero;
        $mesa->altaMesa();

        $payload = json_encode(array("mensaje" => "Mesa creada con exito"));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Mesa::obtenerTodos();
        $payload = json_encode(array("listaMesas" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}