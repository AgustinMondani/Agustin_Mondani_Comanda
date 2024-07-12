<?php

use Slim\Psr7\Request;

require_once 'modelos/Usuario.php';
require_once 'interfaces/IApiUsable.php';

class UsuarioControles extends Usuario implements IApiUsable{
    
    public function CargarUno($request, $response, $args)
    {
        $roles = array("mozo", "cervecero", "cocinero", "bartender","socio");
        $parametros = $request->getParsedBody();

        $nombre = $parametros['nombre'];
        $clave = $parametros['clave'];
        $tipo = $parametros['tipo'];

        if(!Usuario::existeUsuario($nombre) &&  in_array($tipo, $roles)){

        $usuario = new Usuario();
        $usuario->nombre = $nombre;
        $usuario->clave = $clave;
        $usuario->fecha_registro = date('Y-m-d H:i:s');
        $usuario->tipo = $tipo;
        $usuario->estado = "activo";

        $usuario->altaUsuario();

        $payload = json_encode(array("Exito" => "Usuario creado con exito"));
        }
        else{
            $payload = json_encode(array("Error" => "Usuario ya existente o rol incorrecto"));
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Usuario::obtenerTodos();
        $payload = json_encode(array("listaUsuario" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function ComezarAPreparar($request, $response, $args){
        $rol = $request->getAttribute('rol');
        $id_usuario = $request->getAttribute('id');
        $parametros = $request->getParsedBody();
        $demora = $parametros['demora'];
        $id = $parametros['id'];

        $payload = Venta::preparar($id, $rol, $demora, $id_usuario);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}