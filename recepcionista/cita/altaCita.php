
<?php
    session_start();

    if (!isset($_SESSION['email'])) {
        header("Location: ../index.php");
        exit();
    }

    include_once("../../Clases/Conexion.php");
    $conn = new Conexion();

    $sqlEspecialista = "select * from Especialistas";
    $stmtEspecialista = $conn->seleccionar($sqlEspecialista);

    $sqlUsuario =
        "select
            p.ID_Paciente as ID,
            p.CURP,
            Nombre+' '+Apellido_P+' '+Apellido_M as Nombre
            
        from Paciente p left join Usuario u on p.CURP = u.CURP";

    $stmtUsuario = $conn->seleccionar($sqlUsuario);

    $sqlHorario = "select
                    ID_Horario,
                    FORMAT( cast(Inicio_Horario as datetime), 'hh:mm tt') AS Inicio,
                    FORMAT( cast(Fin_Horario as datetime), 'hh:mm tt') AS Fin
                from Horario where ID_Horario between 1 and 13";

    $stmtHorario= $conn->seleccionar($sqlHorario);

    if ( !empty($_POST) ) {

        $paciente = $_POST['Paciente'];
        $especialista = $_POST['Especialista'];
        $fecha = $_POST['Fecha'];
        $horario = $_POST['Horario'];

        $sqlProcedure =
            '';
        $paramAlta =
            array($curp, $nombre, $apPat, $apMat, $fechaNac,
                $calle, $numero, $colonia, $cp, $ciudad, $estado,
                $telefono, $email, $pwd, $rfc, $sueldo, $horario);

        $exitoUsuario = $conn->insertar($sqlProcedure,$paramAlta);
        if ($exitoUsuario) {
            echo "<script>alert('Se ha registrado con éxito');</script>";
        }
    }


    //$resConsulta = $conn->seleccionar($sql);
    
    
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear cuenta</title>
    <link rel="stylesheet" href="../../css/styles.css">
    

</head>
<body>
    <div class="header">
    <h1>Clínica de especialidad</h1>
    </div>
    

    <div class=" centrar">
        <h2>Registro</h2>
        
    </div>
    <form class="crear-cuenta" action="procesarCita.php" method="POST">
            <fieldset>
                <legend>Datos personales</legend>

                <label for="paciente">Seleccionar Un Paciente</label>
                <select name="Paciente" id="paciente" required>
                    <option value="" disabled selected >Seleccione un Paciente</option>
                        <?php
                            if($stmtUsuario){
                                foreach ($stmtUsuario as $row) {
                                    echo '<option value ="'.$row['ID'] .'" >';
                                    echo $row['CURP'] . '    -    ' .$row['Nombre'] ;
                                    echo '</option>';
                                }     
                            }
                        ?>
                </select>

                <label for="especialista">Especialista</label>
                <select name="Especialista" id="especialista" required>
                    <option value="" disabled selected >Seleccione un especialista</option>
                    <?php
                        if($stmtEspecialista){
                            foreach ($stmtEspecialista as $row) {
                                echo '<option value ="'.$row['ID'].'" >';
                                echo $row['Especialidad'] . ' ' .$row['Nombre'] . ' - Costo consulta: $' . $row['Costo']  ;
                                echo '</option>';
                            }     
                        }
                    ?>
                </select>

                <label for="fecha">Fecha de Cita</label>
                <input type="date" name="Fecha" required>

                <label for="horario">Horario de Cita</label>
                <select name="Horario" id="horario" required>
                    <option value="" disabled selected >Seleccione un Horario</option>
                    <?php
                        if($stmtHorario){
                            var_dump($stmtHorario);
                            foreach ($stmtHorario as $row) {
                                echo '<option value ="'.$row['ID_Horario'] .'" >';
                                echo $row['Inicio'] . ' - ' .$row['Fin'];
                                echo '</option>';
                            }     
                        }
                    ?>
                </select>

                
            </fieldset>

            
            <button type="submit" class="boton-confirmar">Confirmar</button>
    </form>

        <div class="logout centrar">
            <a class="border" href="../inicioRecepcionista.php">Regresar al menú principal</a>
        </div>

   
    
</body>
</html>