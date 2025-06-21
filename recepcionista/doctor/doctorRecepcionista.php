
<?php
    session_start();

    if (!isset($_SESSION['email'])) {
        header("Location: ../index.php");
        exit();
    }

    include_once("../../Clases/Conexion.php");
    $conn = new Conexion();
    $sql = 

    "select med.ID_Medico as ID, us.CURP, us.Nombre+' '+Apellido_P+' '+Apellido_M as Nombre, esp.Nombre as Especialidad, Telefono, Email, emp.Sueldo, us.Estatus
    
    from Medico med left join Empleado emp on med.ID_Empleado = emp.ID_Empleado
    left join Usuario us on us.CURP = emp.CURP
    left join Especialidad esp on med.ID_Especialidad = esp.ID_Especialidad";


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


    <h3>Medicos Registrados en el Sistema</h3>
    <table class="tabla-consultas">
        <thead>
            <tr>
                <th>ID</th>
                <th>CURP</th>
                <th>NOMBRE</th>
                <th>ESPECIALIDAD</th>
                <th>TELEFONO</th>
                <th>EMAIL</th>
                <th>SUELDO</th>
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
                echo "<td>" . $row['Especialidad'] . "</td>";
                echo "<td>" . $row['Telefono'] . "</td>";
                echo "<td>" . $row['Email'] . "</td>";
                echo "<td>" . $row['Sueldo'] . "</td>";
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