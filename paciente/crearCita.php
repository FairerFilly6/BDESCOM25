<?php
    session_start();

    if (!isset($_SESSION['email'])) {
        header("Location: ../index.php");
        exit();
    }

    include_once("../Clases/Conexion.php");
    $conn = new Conexion();
    
    if(!empty($_POST)){

        
        $usuario = 'SELECT P.ID_Paciente AS ID FROM Paciente P
                    INNER JOIN Usuario U ON P.CURP = U.CURP
                    WHERE U.Email = ?';
        $paramsConsultaID = array($_SESSION['email']);
        $consultaID = $conn->seleccionar($usuario, $paramsConsultaID);
        
        
        
        if ($consultaID) {
            $resultadoID = $consultaID->fetch(PDO::FETCH_ASSOC);
            $idPaciente = $resultadoID['ID'];
            $idMedico = $_POST['idMedico'];
            $fechaCita = $_POST['fecha'];
            $fechaReservacion = date("Y-m-d");
            $idhorario = $_POST['horario'];
            $idConsultorio = 1;
            $estatusCita = 1;

            

            $crearFactura = "INSERT INTO Factura VALUES (?,?,?)";
            $fechaFactura = date("Y-m-d");
            $conceptoFactura = "Servicio de consulta medica";
            $estatusFactura = "Pendiente";
            $paramsFactura = array($fechaFactura,$conceptoFactura, $estatusFactura);
            $insercionFactura = $conn->insertar($crearFactura,$paramsFactura);

            if ($insercionFactura) {
                // $factura = "SELECT ID_Factura AS ID FROM Factura WHERE Fecha = ?";
                // $paramsConsultaFactura = array($fechaFactura);
                // $facturaID = $conn->seleccionar($factura,$paramsConsultaFactura);
                // if ($facturaID) {
                    // $resultadoFactura = $facturaID->fetch(PDO::FETCH_ASSOC);
                    // $idFactura = $resultadoFactura['ID'];
                    $idFactura = $conn->lastInsertId();

                    $insertarCita = "INSERT INTO Cita VALUES (?,?,?,?,?,?,?,?,0)";
                    $paramsCita = array($idPaciente,$idhorario,$idMedico,$fechaCita,$fechaReservacion,$idFactura,$idConsultorio,$estatusCita);
                    $insercionCita = $conn->insertar($insertarCita,$paramsCita);
                    if ($insercionCita) {
                         header("Location: mostrarCitasPaciente.php");
                    }

                
                
            }

            

        }

    


    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>