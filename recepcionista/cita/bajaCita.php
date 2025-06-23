
<?php
    session_start();

    if (!isset($_SESSION['email'])) {
        header("Location: ../index.php");
        exit();
    }

    include_once("../../Clases/Conexion.php");
    $conn = new Conexion();

    $sql = 

    "select
        cit.Folio_Cita as Folio, med.ID_Medico,
        uM.Nombre+' '+uM.Apellido_P+' '+uM.Apellido_M as Medico,
        uP.Nombre+' '+uP.Apellido_P+' '+uP.Apellido_M as Paciente,
        cit.Fecha_Cita as Fecha,
        format( cast(hor.Inicio_Horario as datetime), 'hh:ss tt' ) + ' - ' + format( cast(hor.Fin_Horario as datetime), 'hh:ss tt' ) as Horario,
        con.Numero as Consultorio,
        eCit.EstatusCita as Estatus

    from
        Cita cit left join Horario hor on cit.ID_Horario=hor.ID_Horario
        left join Paciente pac on cit.ID_Paciente=pac.ID_Paciente
        left join Usuario uP on pac.CURP = uP.CURP
        left Join Medico med on cit.ID_Medico=med.ID_Medico
        left join Empleado emp on med.ID_Empleado=emp.ID_Empleado
        left join Usuario uM on uM.CURP = emp.CURP
        left join EstatusCita eCit on cit.ID_EstatusCita = eCit.ID_EstatusCita
        left join Consultorio con on cit.ID_Consultorio = con.ID_Consultorio
    where cit.ID_EstatusCita in (1,2)";


    $resConsulta = $conn->seleccionar($sql);

    $resLista = $conn->seleccionar($sql);

    if ( !empty($_POST) ) {

        $cita = $_POST['Cita'];
        $usuario = $_POST['Usuario'];

        $sqlProcedure ='exec SP_CANCELACION_CITA ?,?';
        $paramAlta =array($cita, $usuario);

        $exitoUsuario = $conn->insertar($sqlProcedure,$paramAlta);
        if ($exitoUsuario) {
            echo "<script>alert('Se ha cancelado con éxito'); window.location.href = 'bajaCita.php';</script>";
        }
    }


    //$resConsulta = $conn->seleccionar($sql);
    
    
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dar de Baja una Cita</title>
    <link rel="stylesheet" href="../../css/styles.css">
    

</head>
<body>
    <div class="header">
    <h1>Clínica de especialidad</h1>
    </div>
    

    <div class=" centrar">
        <h2>Baja Cita</h2>
        
    </div>

    <div class="menu centrar">
        <table class="tabla-consultas">
            <thead>
                <tr>
                    <th>FOLIO</th>
                    <th>MEDICO</th>
                    <th>PACIENTE</th>
                    <th>FECHA</th>
                    <th>HORARIO</th>
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
                        echo "<td>" . $row['Consultorio'] . "</td>";
                        echo "<td>" . $row['Estatus'] . "</td>";
                        echo "</tr>";
                    }
                }
            ?>

            </tbody>
        </table>
    </div>


    <form class="crear-cuenta" action="bajaCita.php" method="POST">
            <fieldset>

                <label for="usuario">Usuario a Cancelar</label>
                <select name="Usuario" id="usuario" required>
                    <option value="" disabled selected >Seleccione una Usuario</option>

                    <option value="1">Paciente</option>

                    <option value="2">Medico</option>
                </select>

                <label for="cita">Seleccionar Una Cita</label>
                <select name="Cita" id="cita" required>
                    <option value="" disabled selected >Seleccione una Cita</option>
                        <?php
                            if($resLista){
                                foreach ($resLista as $row) {
                                    echo '<option value ="'.$row['Folio'] .'" >';
                                    echo $row['Folio'] ;
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