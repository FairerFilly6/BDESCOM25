<?php

class Conexion {
    private $conn;

    public function __construct() {
        $this->conectar();
    }

    private function conectar() {

        /*
        $host = "DESKTOP-81BAFFF";
        $db = "ClinicaEspecialidadV6";
        $username = "sa";
        $password = "Admin123";
        */

        $host = "LAPTOP-7AU5T3D0";
        $db = "Clinica";
        $username = "userapela";
        $password = "userapela";


        try {
            $this->conn = new PDO("sqlsrv:Server=$host;Database=$db", $username, $password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exp) {
            echo "Error al conectar a la base de datos: " . $exp->getMessage();
            exit;
        }
    }

    public function seleccionar($consulta, $parametros = []) {
        $stmt = $this->conn->prepare($consulta);
        $stmt->execute($parametros);
        return $stmt;
    }

    public function insertar($consulta, $parametros) {
        $stmt = $this->conn->prepare($consulta);
        $stmt->execute($parametros);
        return $stmt->rowCount() > 0;
    }

    public function modificar($consulta, $parametros) {
        return $this->insertar($consulta, $parametros);
    }

    public function eliminar($consulta, $parametros) {
        return $this->insertar($consulta, $parametros);
    }

    public function lastInsertId() {
        return $this->conn->lastInsertId();
    }

    public function getConexion() {
        return $this->conn;
    }
}
?>
