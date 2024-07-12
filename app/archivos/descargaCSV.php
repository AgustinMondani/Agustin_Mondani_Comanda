<?php
include_once 'base_datos/AccesoDatos.php';

function descargarCSV($request, $response, $args) {
    try {

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM producto");
        $consulta->execute();

        $ruta = 'archivos/productosCSV.csv';

        $archivo = fopen($ruta, 'w');

        if ($archivo === false) {
            throw new Exception("No se pudo abrir el archivo para escritura.");
        }

        $header = array('id', 'nombre', 'precio', 'area_preparacion');
        fputcsv($archivo, $header);

        while ($row = $consulta->fetch(PDO::FETCH_ASSOC)) {
            fputcsv($archivo, $row);
        }

        fclose($archivo);


        $payload = json_encode(array("Exito" => "Exportado correctamente"));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');

    } catch (PDOException $e) {
        $response->getBody()->write("Error en la base de datos: " . $e->getMessage());
        return $response->withHeader('Content-Type', 'application/json');
    } catch (Exception $e) {
        $response->getBody()->write("Error: " . $e->getMessage());
        return $response->withHeader('Content-Type', 'application/json');
    }
}