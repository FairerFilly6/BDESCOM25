<?php

    class Conexion{
        function conectar() {

            $host = "DESKTOP-81BAFFF";
            $db = "ClinicaEspecialidadV1";
            $username = "sa";
            $password = "Admin123";
            $puerto = 1433;
            
            // $conn = new PDO( "sqlsrv:server=$serverName ; Database=AdventureWorks", "", "");  
             

            try{
                
                //  $conn = new PDO("sqlsrv:Server=$host,$puerto;Database=$db",$username,$password);
                $conn = new PDO("sqlsrv:Server=$host;Database=$db", $username, $password);
                // echo "Conexion realizada";
                return $conn;
                
            }catch( PDOException $exp){
                echo "<br/>";
                echo $exp;
                echo "<br/>";
                echo "Error al conectar a la base";
                
            }
            return null;
            exit;

        }
        
        function seleccionar($consulta, $parametros = []) {
            $conn = (new Conexion())->conectar();
            if (!empty($parametros)) {
                $stmt = $conn->prepare($consulta);
                $stmt->execute($parametros);
                return $stmt;
            } else {
                return $conn->query($consulta);
            }
        }   


        function insertar($consulta,$parametros){
            $conn = (new Conexion())->conectar();
            $insercion = $conn->prepare($consulta);  
            $exitoInsercion = $insercion->execute($parametros);  
            if ($exitoInsercion) {
                return TRUE;
            }
            return FALSE;
        }

        function modificar($consulta,$parametros){
            $conn = (new Conexion())->conectar();
            $modificacion = $conn->prepare($consulta);  
            $exitoInsercion = $modificacion->execute($parametros);  
            if ($exitoInsercion) {
                return TRUE;
            }
            return FALSE;
        }

        function eliminar($consulta, $parametros){
            $conn = (new Conexion())->conectar();
            $eliminacion = $conn->prepare($consulta);
            $exitoEliminacion = $eliminacion->execute($parametros);
            return $exitoEliminacion;
        }



    }



?>