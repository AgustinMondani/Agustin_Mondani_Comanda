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
    public static function disponible($id_mesa){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id FROM mesa WHERE id = :id AND estado = 'abierta'");
        $consulta->bindValue(':id', $id_mesa, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetch(PDO::FETCH_ASSOC);
    }

    public static function obtenerTodos(){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, estado, numero FROM mesa");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
    }

    public static function existeMesa($id_mesa){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT numero FROM mesa WHERE id = :id");
        $consulta->bindValue(':id', $id_mesa, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetch(PDO::FETCH_ASSOC);
    }

    public static function modificarMesa($id_mesa, $estado){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE mesa SET estado = :estado WHERE id = :id_mesa");
        $consulta->bindValue(':id_mesa', $id_mesa, PDO::PARAM_INT);
        $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->rowCount();
    }

    public static function estadoMesa($id_mesa){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT estado FROM mesa WHERE id = :id");
        $consulta->bindValue(':id', $id_mesa, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetch(PDO::FETCH_COLUMN);
    }

    public static function existeNumeroMesa($numero){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT numero FROM mesa WHERE numero = :numero");
        $consulta->bindValue(':numero', $numero, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetch(PDO::FETCH_ASSOC);
    }
}    