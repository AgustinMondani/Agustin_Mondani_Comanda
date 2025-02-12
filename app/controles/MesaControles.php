<?php

require_once 'modelos/Mesa.php';
require_once 'interfaces/IApiUsable.php';

class MesaControles extends Mesa implements IApiUsable{
    
    public function CargarUno($request, $response, $args)
    {   
        $parametros = $request->getParsedBody();

        $numero = $parametros['numero'];

        if(!Mesa::existeNumeroMesa($numero)){
            $mesa = new Mesa();
            $mesa->estado = "abierta";
            $mesa->numero = (int)$numero;
            $mesa->altaMesa();
    
            $payload = json_encode(array("mensaje" => "Mesa creada con exito"));
        }
        else{
            $payload = json_encode(array("Error" => "Mesa ya existente"));
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Mesa::obtenerTodos();
        $payload = json_encode(array("listaMesas" => $lista));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args){
    
        $estados = array("con cliente esperando pedido", "con cliente comiendo", "con cliente pagando", "cerrada", "abierta");

        $rol = $request->getAttribute('rol');

        $parametros = json_decode($request->getBody()->getContents(), true);
        $numero = $parametros['numero'];
        $estado = $parametros['estado'];
        $id_mesa = $parametros['id_mesa'];

        $estadoActual = Mesa::estadoMesa($id_mesa);
        
        $sePuedeCerrar = $estadoActual == 'con cliente pagando' || $estadoActual == 'abierta';

        if($rol != "socio" && $estado == "cerrada" &&  $sePuedeCerrar){
            $payload = json_encode(array("Error" => "Solo el socio puede cerrar una mesa y no debe estar en uso."));
        }
        else{
            if(Mesa::existeMesa($id_mesa) &&  in_array($estado, $estados)){
                if(Mesa::modificarMesa($id_mesa ,$estado)){
                    $payload = json_encode(array("Exito" => "Mesa modificado correctamente"));
                }
                else{
                    $payload = json_encode(array("Error" => "La mesa no se ha modificado"));
                }
            }else{
                $payload = json_encode(array("Error" => "Datos ingresados incorrectos(numero, estado)"));
            }
        }


        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}