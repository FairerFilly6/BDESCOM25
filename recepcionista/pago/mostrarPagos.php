
<?php
    session_start();

    if (!isset($_SESSION['email'])) {
        header("Location: ../index.php");
        exit();
    }

    include_once("../../Clases/Conexion.php");
    $conn = new Conexion();
    $sql = "SELECT * FROM Pago";


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
    

    <h2>Pagos de la clínica</h2>
    <table class="tabla-consultas">
        <thead>
            <tr>
                <th>ID</th>
                <th>Factura</th>
                <th>Venta</th>
                <th>Método de pago</th>
                <th>Fecha de pago</th>
                <th>Monto</th>
                <th>Estatus</th>
            </tr>
        </thead>
        <tbody>

        <?php
        if($resConsulta){
            foreach($resConsulta as $row){
                echo "<tr>";
                echo "<td>" . $row['ID_Pago'] . "</td>";
                echo "<td>" . $row['ID_Factura'] . "</td>";
                echo "<td>" . $row['ID_Venta'] . "</td>";
                echo "<td>" . $row['Metodo_Pago'] . "</td>";
                echo "<td>" . $row['Fecha_Pago'] . "</td>";
                echo "<td>" . $row['Total'] . "</td>";
                echo "<td>" . $row['Estatus_Pago'] . "</td>";
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