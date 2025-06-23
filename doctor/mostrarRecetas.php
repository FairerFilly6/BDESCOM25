
<?php
    session_start();

    if (!isset($_SESSION['email'])) {
        header("Location: ../index.php");
        exit();
    }

    include_once("../Clases/Conexion.php");
    $conn = new Conexion();
    $sql = "SELECT M.ID_Medico AS id
            FROM Medico M
            LEFT JOIN Empleado E ON M.ID_Empleado = E.ID_Empleado
            LEFT JOIN Usuario U ON E.CURP = U.CURP
            WHERE U.Email = ?";
    $params = [$_SESSION["email"]];

    $stmt = $conn->seleccionar($sql, $params);


    if($stmt){
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);


        if ($row) {
            $recetasConsulta = 
                "SELECT 
                Re.Folio_Receta AS Folio , 
                Cit.Folio_Cita AS Cita, 
                CONCAT (U.Nombre , ' ', U.Apellido_P, ' ', U.Apellido_M) AS Paciente,
                Re.Diagnostico,
                Re.Observaciones,
                Re.Tratamiento,
                Cit.Fecha_Cita
            FROM Receta Re
            LEFT JOIN Cita Cit ON Re.Folio_Cita = Cit.Folio_Cita
            LEFT JOIN Paciente Pa ON Cit.ID_Paciente = Pa.ID_Paciente
            LEFT JOIN Usuario U On PA.CURP = U.CURP
            WHERE Cit.ID_Medico = ? 
            ORDER BY Cit.Fecha_Cita ASC";
            $paramRecetas = [$row["id"]];

            $resRecetas = $conn->seleccionar($recetasConsulta, $paramRecetas);

            
        }



        

    }
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>ClinicaDeEspecialidad</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>

<div class="header">
    <h1>Clínica de especialidad</h1>
</div>

<div class="menu centrar">
    <h2>Bienvenido Paciente</h2>
    <h3>Recetas creadas por citas</h3>
    <table class="tabla-consultas">
        <thead>
            <tr>
                <th>Receta</th>
                <th>Cita</th>
                <th>Paciente</th>
                <th>Diagnóstico</th>
                <th>Observaciones</th>
                <th>Tratamiento</th>
                <th>Fecha de cita</th>
            </tr>
        </thead>
        <tbody>

        <?php
        if($resRecetas){
            foreach($resRecetas as $row){
                echo "<tr>";
                echo "<td>" . $row['Folio'] . "</td>";
                echo "<td>" . $row['Cita'] . "</td>";
                echo "<td>" . $row['Paciente'] . "</td>";
                echo "<td>" . $row['Diagnostico'] . "</td>";
                echo "<td>" . $row['Observaciones'] . "</td>";
                echo "<td>" . $row['Tratamiento'] . "</td>";
                echo "<td>" . $row['Fecha_Cita'] . "</td>";
                echo "</tr>";
            }
        }
        ?>

        </tbody>
    </table>
   

    

    </div>

    <div class="logout centrar">
        <a class="border" href="inicioDoctor.php">Regresar al menú principal</a>
    </div>
    
    <div class="logout centrar">
        <a class="border" href="#">Cerrar sesión</a>
    </div>


   
</div>

</body>
</html>