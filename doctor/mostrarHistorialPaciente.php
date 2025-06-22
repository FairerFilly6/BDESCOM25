

<?php
    session_start();

    if (!isset($_SESSION['email'])) {
        header("Location: ../index.php");
        exit();
    }
    include_once("../Clases/Conexion.php");
        
    $conn = new Conexion();

    $consultaIdMed = "SELECT M.ID_Medico AS id
            FROM Medico M
            LEFT JOIN Empleado E ON M.ID_Empleado = E.ID_Empleado
            LEFT JOIN Usuario U ON E.CURP = U.CURP
            WHERE U.Email = ?";
    $paramIdMed = [$_SESSION["email"]];

    $resIdMed = $conn->seleccionar($consultaIdMed, $paramIdMed);

    if (!empty($_POST)){
        $idPaciente  = $_POST['paciente'];
         $datosPaciente = "SELECT 
                        Pa.ID_Paciente AS id,
                        CONCAT (U.Nombre,' ', U.Apellido_P, ' ', U.Apellido_M) AS Paciente,
                        Pa.Estatura, 
                        Pa.Peso,
                        Pa.Tipo_Sangre AS TipoSangre,
                        Pa.Alergia,
                        Pa.Padecimientos
                    FROM Paciente Pa
                    LEFT JOIN Usuario U ON Pa.CURP = U.CURP
                    WHERE Pa.ID_Paciente = ?";

        $paramDatos = array($idPaciente);
        $stmt = $conn->seleccionar($datosPaciente, $paramDatos);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);


        $rowIdMed = $resIdMed->fetch(PDO::FETCH_ASSOC); 

        $idMed = $rowIdMed['id'];

        $consRecetas = "SELECT 
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
            WHERE Cit.ID_Medico = ? AND Cit.ID_Paciente = ?
            ORDER BY Cit.Fecha_Cita ASC";

            $paramReceta = array($idMed, $idPaciente);

         $resRecetas = $conn->seleccionar($consRecetas, $paramReceta);


        $alergias = "El paciente no tiene alergias registradas";
        if(!($row['Alergia'] == "")) {
            $alergias = $row['Alergia'];
        }

        $padecimientos = "El paciente no tiene padecimientos registrados";
        if(!($row['Padecimientos'] == "")) {
            $padecimientos = $row['Padecimientos'];
        }
        

        
    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ClinicaDeEspecialidad</title>
    <link rel="stylesheet" href="../css/styles.css">
    

</head>
<body>
    <div class="header">
    <h1>Clínica de especialidad</h1>
    </div>
    

    <div class=" centrar">
        <h2>Ver historial médico del paciente</h2>
        
    </div>

    <div class="centrar">
        <h2>Paciente: <?php  echo $row['Paciente'] ?></h2>
        <h3>Estatura   <?php  echo $row['Estatura'] ?></h3>
        <h3>Peso   <?php  echo $row['Peso'] ?></h3>
        <h3>Tipo de sangre   <?php  echo $row['TipoSangre'] ?></h3>
        <h3>Alergias</h3>
        <p> <?php  echo $alergias; ?></p>
        <h3>Padecimientos</h3>
        <p> <?php echo $padecimientos; ?></p>


         <h3>Resumen de recetas del paciente</h3>
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
            <a class="border" href="../inicioDoctor.php">Regresar al menú principal</a>
        </div>

   
    
</body>
</html>