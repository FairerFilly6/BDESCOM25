

<?php
    session_start();

    if (!isset($_SESSION['email'])) {
        header("Location: ../index.php");
        exit();
    }
    include_once("../Clases/Conexion.php");
        
    $conn = new Conexion();

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


    </div>



   

        <div class="logout centrar">
            <a class="border" href="../inicioDoctor.php">Regresar al menú principal</a>
        </div>

   
    
</body>
</html>