<?php
    session_start();

    if (!isset($_SESSION['email'])) {
        header("Location: ../index.php");
        exit();
    }

    include_once("../Clases/Conexion.php");

    $conn = new Conexion();
    $especialistas = 'SELECT * FROM Especialistas';
    $stmt = $conn->seleccionar($especialistas);

    $horario = 'SELECT * FROM IntervalosDisponiblesCitas';
    $consultaHorario = $conn->seleccionar($horario);


?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>ClinicaDeEspecialidad</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>

<div class="header">
    <h1>Clínica de especialidad</h1>
</div>

<div class="menu centrar">
    <h2>Bienvenido Paciente</h2>
    <h3>Agendar cita</h3>

    <form class="formulario-cita" method="POST" action="procesarCita.php">
    

        <label for="especialidad">Especialista</label>
        <select name="especialidad" id="especialidad" required>
            <option value="" disabled selected >Seleccione una especialidad</option>
            <?php
                if($stmt){
                    foreach ($stmt as $row) {
                        echo '<option value ="'.$row['ID'] .'" >';
                        echo $row['Especialidad'] . ' ' .$row['Nombre'] . ' - Costo consulta: $' . $row['Costo']  ;
                        echo '</option>';
                    }     
                }
            ?>
        </select>

        
        <h5>Considere que no se pueden reservar citas con menos de 48hrs de anticipacion ni 3 meses de antelacion</h5>
        <label for="fecha">Fecha:</label>
        <input required name="fecha" type="date">

        <label for="horario">Horario</label>
        <select name="horario" id="horario" required>
            <option value="" disabled selected >Seleccione un horario</option>
            <?php
                if($consultaHorario){
                    foreach ($consultaHorario as $row) {
                        echo '<option value ="'.$row['ID'] .'" >';
                        echo $row['Horario']   ;
                        echo '</option>';
                    }     
                }
            ?>
        </select>

        <button type="submit" class="boton-confirmar">Verificar disponibilidad del doctor</button>



    </form>

    
    <div class="logout centrar">
        <a class="border" href="inicioPaciente.php">Regresar al menú principal</a>
    </div>

    <div class="logout centrar">
        <a class="border" href="#">Cerrar sesión</a>
    </div>


   
</div>

</body>
</html>