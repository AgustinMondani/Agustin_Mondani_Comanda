<?php
    include_once "base_datos/AccesoDatos.php";
class Orden{

    public $id;
    public $codigo; //5char
    public $id_mesa; // id de la mesa que ordeno
    public $id_mozo;
    public $estado; // listo para entregar, en curso
    public $hora_pedido; 
    public $cliente_nombre;

    public function altaOrden()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos
        ->prepararConsulta("INSERT INTO orden (codigo, id_mesa, id_mozo, estado, hora_pedido, cliente_nombre)
         VALUES (:codigo, :id_mesa, :id_mozo, :estado, :hora_pedido, :cliente_nombre)");

        $consulta->bindValue(':codigo', $this->codigo, PDO::PARAM_STR);
        $consulta->bindValue(':id_mesa', $this->id_mesa, PDO::PARAM_INT);
        $consulta->bindValue(':id_mozo', $this->id_mozo);
        $consulta->bindValue(':estado', $this->estado);
        $consulta->bindValue(':hora_pedido', $this->hora_pedido);
        $consulta->bindValue(':cliente_nombre', $this->cliente_nombre);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, codigo, id_mesa, id_mozo, estado, hora_pedido, cliente_nombre FROM orden");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
    }
}    