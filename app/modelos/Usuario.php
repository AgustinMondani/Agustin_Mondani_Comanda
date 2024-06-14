<?php
    include_once "base_datos/AccesoDatos.php";
class Usuario{

    public $id;
    public $nombre;
    public $clave;
    public $fecha_registro; //  #bartender #cerveceros #cocineros #mozos
    public $tipo;
    public $estado; // #supedndido #activo

    public function altaUsuario()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO usuario (nombre, clave, fecha_registro, tipo, estado) VALUES (:nombre, :clave, :fecha_registro, :tipo, :estado)");
        $claveHash = password_hash($this->clave, PASSWORD_DEFAULT);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $claveHash);
        $consulta->bindValue(':fecha_registro', $this->fecha_registro);
        $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre, clave, fecha_registro, tipo, estado FROM usuario");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Usuario');
    }
}    