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

    public function ModificarUno($request, $response, $args){

        $roles = array("mozo", "cervecero", "cocinero", "bartender","socio");
        $estados = array("activo", "suspendido");

        $parametros = json_decode($request->getBody()->getContents(), true);

        $nombre = $parametros['nombre'];
        $estado = $parametros['estado'];
        $tipo = $parametros['tipo'];

        if(Usuario::existeUsuario($nombre) &&  in_array($tipo, $roles) && in_array($estado, $estados)){
            if(Usuario::modificarUsuario($nombre, $estado, $tipo)){
                $payload = json_encode(array("Exito" => "Usuario modificado correctamente"));
            }
            else{
                $payload = json_encode(array("Error" => "El usuario no se ha modificado" . $nombre . $estado . $tipo));
            }
        }else{
            $payload = json_encode(array("Error" => "Datos ingresados incorrectos(nombre, estado, tipo)" . $nombre . $estado . $tipo));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ComezarAPreparar($request, $response, $args){
        $rol = $request->getAttribute('rol');
        $id_usuario = $request->getAttribute('id');
        $parametros = $parametros = json_decode($request->getBody()->getContents(), true);
        $demora = $parametros['demora'];
        $id = $parametros['id'];

        $payload = Venta::preparar($id, $rol, $demora, $id_usuario);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function FinalizarPreparacion($request, $response, $args){
        $id_usuario = $request->getAttribute('id');
        $parametros = $parametros = json_decode($request->getBody()->getContents(), true);
        $id = $parametros['id'];

        $payload = Venta::finalizar($id, $id_usuario);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}