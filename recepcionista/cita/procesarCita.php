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
        $especialista = $_POST['Especialista'];
        $fecha = $_POST['Fecha'];
        $horario = $_POST['Horario'];

        // se busca una cita similar
        $sqlCita = 'select * from Cita C where C.Fecha_Cita = ? and C.ID_Horario = ? and C.ID_Medico = ?';
        $paramsCita = array($fecha, $horario, $especialista);
        $stmtCita =  $conn->seleccionar($sqlCita, $paramsCita);


        $rowCons = $stmtCita->fetch(PDO::FETCH_ASSOC);


        $fechaHoy = new DateTime(); 
        $fechaCita = new DateTime($fecha);

        //validamos que la fecha actual sea menor que la fecha de cita
        $validezFecha = $fechaCita > $fechaHoy;

        //validamos que la cita se agende con al menos 48hrs de anticipacion        
        $intervaloSegundos = $fechaCita->getTimestamp() - $fechaHoy->getTimestamp() ;
        $horasDiferencia = $intervaloSegundos / 3600;  
        $validez48hrs = $horasDiferencia >= 48;

        //validamos que la cita se agende con maximo 3 meses de antelacion
        $diff = date_diff($fechaHoy, $fechaCita);
        $mesesTotales = ($diff->y * 12) + $diff->m;

        $validez3meses = $mesesTotales <= 3;

        //validamos que la hora de la cita este dentro del horario del doctor
        $consultaHorario = 'SELECT 1
                            FROM Medico M
                            JOIN Empleado E ON M.ID_Empleado = E.ID_Empleado
                            JOIN Horario HMedico ON E.ID_Horario = HMedico.ID_Horario
                            JOIN Horario HCita ON HCita.ID_Horario = ?
                            WHERE M.ID_Medico = ?
                            AND HCita.Inicio_Horario >= HMedico.Inicio_Horario
                            AND HCita.Fin_Horario <= HMedico.Fin_Horario';
        $paramHorario = array($horario,$especialista);
        $horarioValido = $conn->seleccionar($consultaHorario,$paramHorario);
        $validezHorario = $horarioValido && $horarioValido->rowCount() < 0;

        //validamos que el paciente no tenga citas previas con el doctor
        
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
            $idPaciente = $resultadoID['ID'];
             $consultaCitasPrevias = 'SELECT 1 FROM Cita C  
                                    WHERE C.ID_Paciente = ? 
                                    AND C.ID_Medico = ?
                                    AND C.ID_EstatusCita IN (1, 2) ';
            $paramCitasPrevias = array($paciente, $especialista);

            $citaValida = $conn->seleccionar($consultaCitasPrevias,$paramCitasPrevias);
            $validezCitas = $citaValida && $citaValida->rowCount() === 0;

            

        }else{
            $validezCitas = false;
        }


        $consultaCitaUnica = 'SELECT 1 FROM Cita WHERE Fecha_Cita = ? AND ID_Horario = ?  AND ID_Medico = ?';
        $paramCitaUnica = array($fecha, $horario,$especialista);

        $citaUnica = $conn->seleccionar($consultaCitaUnica,$paramCitaUnica);
        $validezCitaUnica = $citaUnica && $citaUnica->rowCount() === 0;

        // var_dump($validezFecha);
        // echo '<br>';
        // var_dump($validez48hrs);
        // echo '<br>';
        // var_dump($validez3meses);
        // echo '<br>';
        // var_dump($validezHorario);
        // echo '<br>';
        // var_dump($validezCitas);
        // echo '<br>';

       $citaDisponible = $validezFecha && $validez48hrs && $validez3meses && $validezHorario && $validezCitas && $validezCitaUnica;
        // var_dump($validezFecha);
        // echo '<br>';
    }



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/styles.css">
    <title>Document</title>
</head>
<body>
    <div class="header">
    <h1>Clínica de especialidad</h1>
</div>

<div class="menu centrar">
    <h2>Comprobacion de cita</h2>

    <?php
        $errores = '<p class="alerta rojo">';
        if (!$citaDisponible) {

            if (!$validezFecha) $errores = $errores.'- Ingrese una fecha valida'.'<br>';
            
            if (!$validez48hrs) $errores = $errores.'- No se pueden reservar citas con menos de 48hrs de anticipacion'.'<br>';

            if (!$validez3meses) $errores = $errores.'- No se pueden reservar citas con más de 3 meses de antelación'.'<br>';

            if (!$validezHorario) $errores = $errores.'- El especialista no se encuentra disponible en ese horario'.'<br>';

            if (!$validezCitas) $errores = $errores.'- No es posible reservar una cita si ya tiene una pendiente con el mismo especialista'.'<br>';

            if (!$validezCitaUnica) $errores = $errores.'- Esta cita ya esta reservada'.'<br>';

            echo $errores.'</p>';

            echo '<a href="altaCita.php">Buscar otra cita</a>';
        } else {
            echo '<h3 class="alerta verde"> La cita está disponible </h3>
                <form method="post" action="crearCita.php">
                    <!-- Reenviamos los datos ocultos -->
                    <input type="hidden" name="Paciente" value="<?= htmlspecialchars($paciente) ?>">
                    <input type="hidden" name="Especialista" value="<?= htmlspecialchars($especialista) ?>">
                    <input type="hidden" name="Fecha" value="<?= htmlspecialchars($fecha) ?>">
                    <input type="hidden" name="Horario" value="<?= htmlspecialchars($horario) ?>">
                    <input type="hidden" name="confirmar" value="true">
                    <button type="submit" class="boton-confirmar">Confirmar cita</button>
                </form>';
        }
    ?>


    <div class="logout centrar">
        <a class="border" href="#">Cerrar sesión</a>
    </div>


   
</div>

</body>
</html>