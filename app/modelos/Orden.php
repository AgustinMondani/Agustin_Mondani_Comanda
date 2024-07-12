<?php
    include_once "base_datos/AccesoDatos.php";
    include_once "funciones/guardar_imagen.php";
class Orden{

    public $id;
    public $codigo;
    public $id_mesa;
    public $estado;
    public $hora_pedido; 
    public $cliente_nombre;

    public function altaOrden()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos
        ->prepararConsulta("INSERT INTO orden (codigo, id_mesa, estado, hora_pedido, cliente_nombre)
         VALUES (:codigo, :id_mesa, :estado, :hora_pedido, :cliente_nombre)");

        $consulta->bindValue(':codigo', $this->codigo, PDO::PARAM_STR);
        $consulta->bindValue(':id_mesa', $this->id_mesa, PDO::PARAM_INT);
        $consulta->bindValue(':estado', $this->estado);
        $consulta->bindValue(':hora_pedido', $this->hora_pedido);
        $consulta->bindValue(':cliente_nombre', $this->cliente_nombre);
        $consulta->execute();

        try{
            guardarImagen('../ImagenesDeOrdenes/', $this->codigo);
        }
        catch(Exception $ex){
            echo $ex->getMessage();
        }

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, codigo, id_mesa, estado, hora_pedido, cliente_nombre FROM orden");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
    }

    public static function generarCodigo(){
        
        $codigo = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', 5)), 0, 5);

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id FROM orden WHERE codigo = :codigo");
        $consulta->bindValue(':codigo', $codigo);
        $consulta->execute();
        
        while($consulta->fetch()){
            $codigo = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', 5)), 0, 5);

            $consulta = $objAccesoDatos->prepararConsulta("SELECT id FROM orden WHERE codigo = :codigo");
            $consulta->bindValue(':codigo', $codigo);
            $consulta->execute();
        }

        return $codigo;
    }
}    