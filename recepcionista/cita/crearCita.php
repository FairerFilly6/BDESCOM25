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
        
        $mostrar = false;
        
        if ($consultaID) {
            $resultadoID = $consultaID->fetch(PDO::FETCH_ASSOC);
            $paciente = $resultadoID['ID'];
            $especialista = $_POST['Especialista'];
            $fechaCita = $_POST['Fecha'];
            $fechaReservacion = date("Y-m-d");
            $idhorario = $_POST['Horario'];
            $montoDevuelto = 0.0;
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

                    $insertarCita = "INSERT INTO Cita VALUES (?,?,?,?,getdate(),?,?,?,?)";
                    $paramsCita = array($paciente,$idhorario,$especialista,$fechaCita,$idFactura,$idConsultorio,$estatusCita,$montoDevuelto);
                    $insercionCita = $conn->insertar($insertarCita,$paramsCita);

                    if ($insercionCita) {


                        $sql = 
                        "select
                            cit.Folio_Cita as Folio,
                            uM.Nombre+' '+uM.Apellido_P+' '+uM.Apellido_M as Medico,
                            uP.Nombre+' '+uP.Apellido_P+' '+uP.Apellido_M as Paciente,
                            cit.Fecha_Cita as Fecha,
                            format( cast(hor.Inicio_Horario as datetime), 'hh:ss tt' ) + ' - ' + format( cast(hor.Fin_Horario as datetime), 'hh:ss tt' ) as Horario,
                            esp.Nombre as Especialidad,
                            con.Numero as Consultorio,
                            eCit.EstatusCita as Estatus

                        from
                            Cita cit left join Horario hor on cit.ID_Horario=hor.ID_Horario
                            left join Paciente pac on cit.ID_Paciente=pac.ID_Paciente
                            left join Usuario uP on pac.CURP = uP.CURP
                            left Join Medico med on cit.ID_Medico=med.ID_Medico
                            left join Empleado emp on med.ID_Empleado=emp.ID_Empleado
                            left join Usuario uM on uM.CURP = emp.CURP
                            left join Especialidad esp on med.ID_Especialidad = esp.ID_Especialidad
                            left join EstatusCita eCit on cit.ID_EstatusCita = eCit.ID_EstatusCita
                            left join Consultorio con on cit.ID_Consultorio = con.ID_Consultorio where cit.Folio_Cita=?";


                        $resConsulta = $conn->seleccionar($sql, array( $conn->lastInsertId() ));

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
    <title>Cita Creada</title>
    <link rel="stylesheet" href="../../css/styles.css">
    

</head>
<body>
    <div class="header">
    <h1>Clínica de especialidad</h1>
    </div>
    

    <div class=" centrar">
        <h2>Cita Agendada</h2>
        
    </div>


    <table class="tabla-consultas">
        <thead>
            <tr>
                <th>FOLIO</th>
                <th>MEDICO</th>
                <th>PACIENTE</th>
                <th>FECHA</th>
                <th>HORARIO</th>
                <th>ESPECIALIDAD</th>
                <th>CONSULTORIO</th>
                <th>ESTATUS</th>
            </tr>
        </thead>

        <tbody>

        <?php
            if($resConsulta){
                foreach($resConsulta as $row){
                    echo "<tr>";
                    echo "<td>" . $row['Folio'] . "</td>";
                    echo "<td>" . $row['Medico'] . "</td>";
                    echo "<td>" . $row['Paciente'] . "</td>";
                    echo "<td>" . $row['Fecha'] . "</td>";
                    echo "<td>" . $row['Horario'] . "</td>";
                    echo "<td>" . $row['Especialidad'] . "</td>";
                    echo "<td>" . $row['Consultorio'] . "</td>";
                    echo "<td>" . $row['Estatus'] . "</td>";
                    echo "</tr>";
                }
            }
        ?>

        </tbody>
    </table>

    <div class="logout centrar">
            <a class="border" href="altaCita.php">Regresar</a>
    </div>
    
</body>
</html>