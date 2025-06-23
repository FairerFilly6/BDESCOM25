
<?php
    session_start();

    if (!isset($_SESSION['email'])) {
        header("Location: ../index.php");
        exit();
    }

    include_once("../Clases/Conexion.php");
    $conn = new Conexion();

    $consultaIdMed = "SELECT M.ID_Medico AS id
            FROM Medico M
            LEFT JOIN Empleado E ON M.ID_Empleado = E.ID_Empleado
            LEFT JOIN Usuario U ON E.CURP = U.CURP
            WHERE U.Email = ?";
    $paramidMed = [$_SESSION["email"]];

    $stmt = $conn->seleccionar($consultaIdMed, $paramidMed);


    if($stmt){
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $idMedico = $row['id'];

        $obtenerCitasMed = 
            "select
                cit.Folio_Cita as Folio, med.ID_Medico,
                uM.Nombre+' '+uM.Apellido_P+' '+uM.Apellido_M as Medico,
                uP.Nombre+' '+uP.Apellido_P+' '+uP.Apellido_M as Paciente,
                cit.Fecha_Cita as Fecha,
                format( cast(hor.Inicio_Horario as datetime), 'hh:ss tt' ) + ' - ' + format( cast(hor.Fin_Horario as datetime), 'hh:ss tt' ) as Horario,
                con.Numero as Consultorio,
                eCit.EstatusCita as Estatus,
                format(cit.Monto_Devuelto,'N2') as Devolucion

            from
                Cita cit left join Horario hor on cit.ID_Horario=hor.ID_Horario
                left join Paciente pac on cit.ID_Paciente=pac.ID_Paciente
                left join Usuario uP on pac.CURP = uP.CURP
                left Join Medico med on cit.ID_Medico=med.ID_Medico
                left join Empleado emp on med.ID_Empleado=emp.ID_Empleado
                left join Usuario uM on uM.CURP = emp.CURP
                left join EstatusCita eCit on cit.ID_EstatusCita = eCit.ID_EstatusCita
                left join Consultorio con on cit.ID_Consultorio = con.ID_Consultorio
                WHERE cit.ID_Medico = ? AND eCit.ID_EstatusCita in ( 1,2 )
                ";

            $paramCitaMed = array($idMedico);
            $resLista = $conn->seleccionar($obtenerCitasMed,$paramCitaMed);




    }
        
        


    if ( !empty($_POST) ) {

        $cita = $_POST['Cita'];
        $usuario = 2;

        $sqlProcedure ='exec SP_CANCELACION_CITA ?,?';
        $paramAlta =array($cita, $usuario);

        $exitoUsuario = $conn->insertar($sqlProcedure,$paramAlta);
        if ($exitoUsuario) {
            echo "<script>alert('Se ha registrado con éxito'); window.location.href = 'inicioDoctor.php';</script>";
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
    <link rel="stylesheet" href="../css/styles.css">
    

</head>
<body>
    <div class="header">
    <h1>Clínica de especialidad</h1>
    </div>
    

    <div class=" centrar">
        <h2>Baja</h2>
        
    </div>

    <div class="menu centrar">
        <h3>Citas Registradas en el Sistema</h3>
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
                    <th>Devolucion</th>
                </tr>
            </thead>

            <tbody>

            <?php
                if($resLista){
                    foreach($resLista as $row){
                        echo "<tr>";
                        echo "<td>" . $row['Folio'] . "</td>";
                        echo "<td>" . $row['Medico'] . "</td>";
                        echo "<td>" . $row['Paciente'] . "</td>";
                        echo "<td>" . $row['Fecha'] . "</td>";
                        echo "<td>" . $row['Horario'] . "</td>";
                        echo "<td>" . $row['Consultorio'] . "</td>";
                        echo "<td>" . $row['Estatus'] . "</td>";
                        echo "<td>" . $row['Devolucion'] . "</td>";
                        echo "</tr>";
                    }
                }
            ?>

            </tbody>
        </table>
    </div>


    <form class="crear-cuenta" action="cancelarCitaDoctor.php" method="POST">
            <fieldset>

                <label for="cita">Seleccionar Una Cita</label>
                <select name="Cita" id="cita" required>
                    <option value="" disabled selected >Seleccione una Cita</option>
                        <?php
                            $resLista = $conn->seleccionar($obtenerCitasMed,$paramCitaMed);
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
            <a class="border" href="inicioDoctor.php">Regresar al menú principal</a>
        </div>

   
    
</body>
</html>