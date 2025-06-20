<?php
    session_start();

    if (!isset($_SESSION['email'])) {
        header("Location: ../index.php");
        exit();
    }

    include_once("../../Clases/Conexion.php");
    $conn = new Conexion();
    
    if(!empty($_POST)){

        $paciente=$_POST['Paciente'];
        $usuario = 'SELECT P.ID_Paciente AS ID FROM Paciente P
                    INNER JOIN Usuario U ON P.CURP = U.CURP
                    WHERE U.Email = ?';
        $sqlEmailPaciente = 
            'SELECT U.Email AS Email FROM Paciente P
                    INNER JOIN Usuario U ON P.CURP = U.CURP
                    WHERE P.ID_Paciente = ?';
        $stmtEmailPaciente = $conn->seleccionar($sqlEmailPaciente, array($paciente));

        $paramsConsultaID = $stmtEmailPaciente->fetch(PDO::FETCH_ASSOC);

        $consultaID = $conn->seleccionar($usuario, array($paramsConsultaID['Email']));
        
        
        
        if ($consultaID) {
            $resultadoID = $consultaID->fetch(PDO::FETCH_ASSOC);
            $paciente = $resultadoID['ID'];
            $especialista = $_POST['Especialista'];
            $fechaCita = $_POST['Fecha'];
            $fechaReservacion = date("Y-m-d");
            $idhorario = $_POST['Horario'];
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

                    $insertarCita = "INSERT INTO Cita VALUES (?,?,?,?,?,?,?,?)";
                    $paramsCita = array($paciente,$idhorario,$especialista,$fechaCita,$fechaReservacion,$idFactura,$idConsultorio,$estatusCita);
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