
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
        cit.Folio_Cita as Folio,
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
        left join Consultorio con on cit.ID_Consultorio = con.ID_Consultorio";


    $resConsulta = $conn->seleccionar($sql);
    
    
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>ClinicaDeEspecialidad</title>
    <link rel="stylesheet" href="../../css/styles.css">
</head>
<body>

<div class="header">
    <h1>Clínica de especialidad</h1>
</div>

<div class="menu centrar">
    <h2>Bienvenido recepcionista</h2>


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
                        echo "<td>" . $row['Devolucion'] . "</td>";
                        echo "</tr>";
            }
        }
        ?>

        </tbody>
    </table>
</div>
    <div class="logout centrar">
        <a class="border" href="../inicioRecepcionista.php">Regresar al menú principal</a>
    </div>

    <div class="logout centrar">
        <a class="border" href="#">Cerrar sesión</a>
    </div>


   
</div>

</body>
</html>