
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
            $consulta = 
                "SELECT
                    Cit.ID_Medico,
                    Cit.Folio_Cita  AS Folio, 
                    Cit.ID_Paciente,
                    CONCAT(Usr.Nombre, ' ' , Usr.Apellido_P, ' ', Usr.Apellido_M) AS Paciente,
                    Cit.Fecha_Cita AS FechaCita, 
                    CONCAT(
                        LEFT(Ho.Inicio_Horario,5), ' - ',
                        LEFT(Ho.Fin_Horario,5) )AS Horario,
                    Cit.Fecha_Reservacion AS FechaRes, 
                    Con.Numero AS NumConsultorio,
                    Con.Piso AS PisoConsultorio,
                    EC.EstatusCita AS Estatus
                FROM Cita Cit
                LEFT JOIN Horario Ho ON Cit.ID_Horario = Ho.ID_Horario
                LEFT JOIN Consultorio Con ON Cit.ID_Consultorio = Con.ID_Consultorio
                LEFT JOIN Paciente Pac ON Cit.ID_Paciente = Pac.ID_Paciente
                LEFT JOIN EstatusCita EC ON Cit.ID_EstatusCita = EC.ID_EstatusCita
                LEFT JOIN Usuario Usr ON Pac.CURP = Usr.CURP
                WHERE Cit.ID_Medico = ? AND Cit.ID_EstatusCita = 2
                ORDER BY Cit.Fecha_Cita ASC";
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


 <form class="crear-cuenta" action="procesarReceta.php" method="POST">
    <fieldset>
        
        <label for="idCita">Seleccione la cita sobre la que desea generar la receta</label>
        <select name="idCita" required>
            <option value="" disabled selected required>Seleccione una cita</option>
            <?php
                foreach ($resConsulta as $row) {
                    echo "<option value= ". $row['Folio'] .">".  $row['Folio']. " - " . $row['Paciente'] . " </option>";

                }
            ?>
        </select>

    </fieldset>
    
    <button type="submit" class="boton-confirmar">Confirmar</button>
</form>




   

    
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