
<?php
    session_start();

    if (!isset($_SESSION['email'])) {
        header("Location: ../index.php");
        exit();
    }

    include_once("../../Clases/Conexion.php");
    $conn = new Conexion();
    $sql = 
    "select  r.ID_Recepcionista as ID,
        u.CURP, u.Nombre+' '+Apellido_P+' '+Apellido_M as Nombre,
        Email,
        Telefono,
        RFC,
        Sueldo,
        format ( cast( Inicio_Horario as datetime ), 'hh:mm tt' )+' - '+format ( cast( Fin_Horario as datetime ), 'hh:mm tt' ) as Horario,
        u.Estatus
    from Recepcionista r left join Empleado e on r.ID_Empleado = e.ID_Empleado
        left join Usuario u on e.CURP = u.CURP
        left join Horario h on e.ID_Horario = h.ID_Horario
    where u.CURP is not null";


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


    <h3>Recepcionistas Registrados en el Sistema</h3>
    <table class="tabla-consultas">
        <thead>
            <tr>
                <th>ID</th>
                <th>CURP</th>
                <th>NOMBRE</th>
                <th>EMAIL</th>
                <th>TELEFONO</th>
                <th>RFC</th>
                <th>SUELDO</th>
                <th>HORARIO</th>
                <th>ESTATUS</th>
            </tr>
        </thead>

        <tbody>

        <?php
        if($resConsulta){
            foreach($resConsulta as $row){
                echo "<tr>";
                echo "<td>" . $row['ID'] . "</td>";
                echo "<td>" . $row['CURP'] . "</td>";
                echo "<td>" . $row['Nombre'] . "</td>";
                echo "<td>" . $row['Email'] . "</td>";
                echo "<td>" . $row['Telefono'] . "</td>";
                echo "<td>" . $row['RFC'] . "</td>";
                echo "<td>" . $row['Sueldo'] . "</td>";
                echo "<td>" . $row['Horario'] . "</td>";
                echo "<td>" . $row['Estatus'] . "</td>";
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