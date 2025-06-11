<?php
    session_start();

    if (!isset($_SESSION['email'])) {
        header("Location: ../index.php");
        exit();
    }

    include_once("../Clases/Conexion.php");
    $conn = new Conexion();
    
    if(!empty($_POST)){
        $idEspecialista = $_POST['especialidad'];
        $fecha = $_POST['fecha'];
        $horario = $_POST['horario'];

        $validacionCita = 'SELECT * FROM Cita C WHERE C.Fecha_Cita = ? AND C.ID_Horario = ? AND C.ID_Medico = ?';

        $params = array($fecha, $horario, $idEspecialista);
        
        $consultaValidacion =  $conn->seleccionar($validacionCita, $params);


        $rowCons = $consultaValidacion->fetch(PDO::FETCH_ASSOC);

        //validamos que la fecha actual sea menor que la fecha de cita
        $fechaHoy = new DateTime(); 
        $fechaCita = new DateTime($fecha);
        $validezFecha = $fechaCita > $fechaHoy;


        //validamos que la cita se agende con al menos 48hrs de anticipacion
        $fechaHoy = new DateTime();                
        $fechaCita = new DateTime($fecha);         

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
        $paramHorario = array($horario,$idEspecialista);
        $horarioValido = $conn->seleccionar($consultaHorario,$paramHorario);
        $validezHorario = $horarioValido && $horarioValido->rowCount() < 0;

        //validamos que el paciente no tenga citas previas con el doctor
        
        $usuario = 'SELECT P.ID_Paciente AS ID FROM Paciente P
                    INNER JOIN Usuario U ON P.CURP = U.CURP
                    WHERE U.Email = ?';
        $paramsConsultaID = array($_SESSION['email']);
        $consultaID = $conn->seleccionar($usuario, $paramsConsultaID);
        
        
        if ($consultaID) {
            $resultadoID = $consultaID->fetch(PDO::FETCH_ASSOC);
            $idPaciente = $resultadoID['ID'];
             $consultaCitasPrevias = 'SELECT 1 FROM Cita C  
                                    WHERE C.ID_Paciente = ? 
                                    AND C.ID_Medico = ?
                                    AND C.ID_EstatusCita IN (1, 2) ';
            $paramCitasPrevias = array($idPaciente, $idEspecialista);

            $citaValida = $conn->seleccionar($consultaCitasPrevias,$paramCitasPrevias);
            $validezCitas = $citaValida && $citaValida->rowCount() === 0;

            

        }else{
            $validezCitas = false;
        }

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

       $citaDisponible = $validezFecha && $validez48hrs && $validez3meses && $validezHorario && $validezCitas;
        // var_dump($validezFecha);
        // echo '<br>';
    }



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Document</title>
</head>
<body>
    <div class="header">
    <h1>Clínica de especialidad</h1>
</div>

<div class="menu centrar">
    <h2>Comprobacion de cita</h2>
    


    


    <?php if (!$citaDisponible): ?>
        
        <?php if (!$validezFecha): ?>
            <h3 class="alerta rojo"> Ingrese una fecha valida </h3>
        <?php endif; ?>
        <?php if (!$validez48hrs): ?>
            <h3 class="alerta rojo"> No se pueden reservar citas con menos de 48hrs de anticipacion </h3>
        <?php endif; ?>
        <?php if (!$validez3meses): ?>
            <h3 class="alerta rojo"> No se pueden reservar citas con más de 3 meses de antelación </h3>
        <?php endif; ?>
        <?php if (!$validezHorario): ?>
            <h3 class="alerta rojo"> El especialista no se encuentra disponible en ese horario </h3>
        <?php endif; ?>
        <?php if (!$validezCitas): ?>
            <h3 class="alerta rojo"> No es posible reservar una cita si ya tiene una pendiente con el mismo especialista </h3>
        <?php endif; ?>

        <a href="agendarCitaPaciente.php">Buscar otra cita</a>
    <?php else: ?>
        <h3 class="alerta verde"> La cita está disponible </h3>
        <form method="post" action="crearCita.php">
            <!-- Reenviamos los datos ocultos -->
            <input type="hidden" name="idMedico" value="<?= htmlspecialchars($idEspecialista) ?>">
            <input type="hidden" name="fecha" value="<?= htmlspecialchars($fecha) ?>">
            <input type="hidden" name="horario" value="<?= htmlspecialchars($horario) ?>">
            <input type="hidden" name="confirmar" value="true">
            <button type="submit" class="boton-confirmar">Confirmar cita</button>
        </form>
    <?php endif; ?>






    <div class="logout centrar">
        <a class="border" href="#">Cerrar sesión</a>
    </div>


   
</div>

</body>
</html>
