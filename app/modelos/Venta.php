<?php
    include_once "base_datos/AccesoDatos.php";
class Venta{

    public $id;
    public $producto;
    public $id_usuario;
    public $codigo;
    public $cantidad;
    public $demora;
    public $estado;

    public function altaVenta()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO venta (producto, id_usuario, codigo, cantidad, demora, estado) VALUES (:producto, :id_usuario, :codigo, :cantidad, :demora, :estado)");
        $consulta->bindValue(':producto', $this->producto, PDO::PARAM_STR);
        $consulta->bindValue(':id_usuario', $this->id_usuario, PDO::PARAM_INT);
        $consulta->bindValue(':codigo', $this->codigo, PDO::PARAM_STR);
        $consulta->bindValue(':cantidad', $this->cantidad, PDO::PARAM_INT);
        $consulta->bindValue(':demora', $this->demora, PDO::PARAM_INT);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT producto, id_usuario, codigo, cantidad, demora, estado FROM venta");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Venta');
    }

    public static function obtenerSegunRol($area_preparacion)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        
        $consultaVentas = $objAccesoDatos->prepararConsulta("SELECT producto, id_usuario, codigo, cantidad, demora, estado FROM venta WHERE  id_usuario IS NULL");
        $consultaVentas->execute();
        $ventas = $consultaVentas->fetchAll(PDO::FETCH_CLASS, 'Venta');
        $resultadosFiltrados = [];

        foreach ($ventas as $venta) {
            $consultaProducto = $objAccesoDatos->prepararConsulta("SELECT * FROM producto WHERE nombre = :producto AND area_preparacion = :area_preparacion");
            $consultaProducto->bindValue(':producto', $venta->producto, PDO::PARAM_INT);
            $consultaProducto->bindValue(':area_preparacion', $area_preparacion, PDO::PARAM_STR);
            $consultaProducto->execute();
            
            $producto = $consultaProducto->fetch(PDO::FETCH_ASSOC);
            
          
            if ($producto) {
                $resultadosFiltrados[] = $venta;
            }
        }
        
        return $resultadosFiltrados;
    }
    
}    