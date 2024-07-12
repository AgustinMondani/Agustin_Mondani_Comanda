<?php
    include_once "base_datos/AccesoDatos.php";
class Mesa{

    public $id;
    public $estado; //“con cliente esperando pedido” ,”con cliente comiendo”, “con cliente pagando” y “cerrada”.
    public $numero;

    public function altaMesa()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO mesa (estado, numero) VALUES (:estado, :numero)");
        $consulta->bindValue(':estado', $this->estado,  PDO::PARAM_STR);
        $consulta->bindValue(':numero', $this->numero, PDO::PARAM_INT);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, estado, numero FROM mesa");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
    }
}    