
<?php
    session_start();

    if (!isset($_SESSION['email'])) {
        header("Location: ../index.php");
        exit();
    }

    include_once("../../Clases/Conexion.php");
    $conn = new Conexion();
    $sql = "SELECT P.ID_Paciente AS ID, U.CURP AS CURP, CONCAT(U.Nombre, ' ',U.Apellido_P,' ' , U.Apellido_M) AS Nombre, 
            CONCAT (U.Calle, ' ', U.Numero, ' ', U.Colonia, ' ', U.Colonia) AS Domicilio,
            U.Fecha_Nac AS FechaNac,
            U.Email AS Email,
            P.Padecimientos AS Padecimientos,
            P.Alergia AS Alergia,
            P.Estatura AS Estatura,
            P.Peso AS Peso,
            P.Tipo_Sangre AS TipoSangre,
            U.Estatus AS Estatus
            FROM Paciente P
            LEFT JOIN Usuario U ON P.CURP = U.CURP";


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
    <h1>Pacientes registrados</h1>
</div>

<div class="menu centrar">
    <h2>Bienvenido Paciente</h2>

    <h3>Citas registradas</h3>
    <table class="tabla-consultas">
        <thead>
            <tr>
                <th>ID</th>
                <th>CURP</th>
                <th>Nombre</th>
                <th>Domicilio</th>
                <th>Fecha de nacimiento</th>
                <th>Email</th>
                <th>Padecimientos</th>
                <th>Alergia</th>
                <th>Estatura</th>
                <th>Peso</th>
                <th>Tipo de Sangre</th>
                <th>Estatus</th>
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
                echo "<td>" . $row['Domicilio'] . "</td>";
                echo "<td>" . $row['FechaNac'] . "</td>";
                echo "<td>" . $row['Email'] . "</td>";
                echo "<td>" . $row['Padecimientos'] . "</td>";
                echo "<td>" . $row['Alergia'] . "</td>";
                echo "<td>" . $row['Estatura'] . "</td>";
                echo "<td>" . $row['Peso'] . "</td>";
                echo "<td>" . $row['TipoSangre'] . "</td>";
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