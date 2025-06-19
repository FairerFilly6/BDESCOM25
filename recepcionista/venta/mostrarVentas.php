
<?php
    session_start();

    if (!isset($_SESSION['email'])) {
        header("Location: ../index.php");
        exit();
    }

    include_once("../../Clases/Conexion.php");
    $conn = new Conexion();
    $sql = "SELECT V.ID_Venta AS ID, CONCAT(U.Nombre, ' ', U.Apellido_P) AS Recepcionista,
                    V.FechaVenta,V.Concepto, P.Total
            FROM Venta V
            LEFT JOIN Recepcionista R ON V.ID_Recepcionista = R.ID_Recepcionista
            LEFT JOIN Empleado E ON R.ID_Empleado = E.ID_Empleado
            LEFT JOIN Usuario U ON E.CURP = U.CURP
            RIGHT JOIN Pago P ON P.ID_Venta = V.ID_Venta";


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
    <h1>Ventas registradas</h1>
</div>

<div class="menu centrar">
    <h2>Bienvenido Paciente</h2>

    <h3>Ventas registradas</h3>
    <table class="tabla-consultas">
        <thead>
            <tr>
                <th>ID</th>
                <th>Recepcionista</th>
                <th>Fecha</th>
                <th>Concepto</th>
                <th>Total</th>
               
            </tr>
        </thead>
        <tbody>

        <?php
        if($resConsulta){
            foreach($resConsulta as $row){
                echo "<tr>";
                echo "<td>" . $row['ID'] . "</td>";
                echo "<td>" . $row['Recepcionista'] . "</td>";
                echo "<td>" . $row['FechaVenta'] . "</td>";
                echo "<td>" . $row['Concepto'] . "</td>";
                echo "<td>" . $row['Total'] . "</td>";
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