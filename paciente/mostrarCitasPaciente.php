
<?php
    session_start();

    if (!isset($_SESSION['email'])) {
        header("Location: ../index.php");
        exit();
    }


    include_once("../Clases/Conexion.php");
    $conn = new Conexion();
    $sql = 'SELECT P.ID_Paciente AS id 
            FROM Paciente P
            LEFT JOIN Usuario U ON P.CURP = U.CURP
            WHERE U.Email = ?';
    $params = [$_SESSION["email"]];

    $stmt = $conn->seleccionar($sql, $params);

    if($stmt){
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $consulta = 
                "SELECT Cit.Folio_Cita AS Folio, 
                    CONCAT(Us.Nombre, ' ', Us.Apellido_P) AS Medico,
                    Esp.Nombre AS Especialidad,
                    Cit.Fecha_Cita AS FechaCita, 
                    CONCAT(
                        LEFT(Ho.Inicio_Horario,5), ' - ',
                        LEFT(Ho.Fin_Horario,5) )AS Horario,
                    Cit.Fecha_Reservacion AS FechaRes, 
                    Con.Numero AS NumConsultorio,
                    Con.Piso AS PisoConsultorio,
                    Fac.Estatus AS Estatus
                FROM Cita Cit
                LEFT JOIN Medico Med ON Cit.ID_Medico = Med.ID_Medico
                LEFT JOIN Empleado Emp ON Med.ID_Empleado = Emp.ID_Empleado
                LEFT JOIN Usuario Us ON Emp.CURP = Us.CURP
                LEFT JOIN Horario Ho ON Cit.ID_Horario = Ho.ID_Horario
                LEFT JOIN Especialidad Esp ON Cit.ID_Especialidad = Esp.ID_Especialidad
                LEFT JOIN Consultorio Con ON Cit.ID_Consultorio = Con.ID_Consultorio
                LEFT JOIN Factura Fac ON Cit.ID_Factura = Fac.ID_Factura
                WHERE Cit.ID_Paciente = ?
                ORDER BY FechaCita DESC";
            $paramConsulta = [$row["id"]];

            $resConsulta = $conn->seleccionar($consulta, $paramConsulta);
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
    <h3>Citas registradas</h3>
    <table class="tabla-consultas">
        <thead>
            <tr>
                <th>Folio</th>
                <th>Médico</th>
                <th>Especialidad</th>
                <th>Fecha cita</th>
                <th>Horario</th>
                <th>Fecha reservacion</th>
                <th>Consultorio</th>
                <th>Piso</th>
                <th>Estatus</th>
            </tr>
        </thead>
        <tbody>

        <?php
        if($resConsulta){
            foreach($resConsulta as $row){
                echo "<tr>";
                echo "<td>" . $row['Folio'] . "</td>";
                echo "<td>" . $row['Medico'] . "</td>";
                echo "<td>" . $row['Especialidad'] . "</td>";
                echo "<td>" . $row['FechaCita'] . "</td>";
                echo "<td>" . $row['Horario'] . "</td>";
                echo "<td>" . $row['FechaRes'] . "</td>";
                echo "<td>" . $row['NumConsultorio'] . "</td>";
                echo "<td>" . $row['PisoConsultorio'] . "</td>";
                echo "<td>" . $row['Estatus'] . "</td>";
                echo "</tr>";
            }
        }
        ?>

        </tbody>
    </table>

    </div>
    <div class="logout centrar">
        <a class="border" href="#">Cerrar sesión</a>
    </div>


   
</div>

</body>
</html>