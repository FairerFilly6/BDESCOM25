
<?php
    session_start();

    if (!isset($_SESSION['email'])) {
        header("Location: ../index.php");
        exit();
    }

    include_once("../../Clases/Conexion.php");
    $conn = new Conexion();
    $sql = "SELECT * FROM Servicio";


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
    <h1>Servicios</h1>
</div>

<div class="menu centrar">
    

    <h2>Servicios de la clínica</h2>
    <table class="tabla-consultas">
        <thead>
            <tr>
                <th>ID</th>
                <th>Servicio</th>
                <th>Descripcion</th>
                <th>Costo</th>
                
               
            </tr>
        </thead>
        <tbody>

        <?php
        if($resConsulta){
            foreach($resConsulta as $row){
                echo "<tr>";
                echo "<td>" . $row['ID_Servicio'] . "</td>";
                echo "<td>" . $row['Nombre'] . "</td>";
                echo "<td>" . $row['Descripcion'] . "</td>";
                echo "<td>" . $row['Costo'] . "</td>";
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