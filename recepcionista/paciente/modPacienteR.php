

<?php
    session_start();

    if (!isset($_SESSION['email'])) {
        header("Location: ../index.php");
        exit();
    }
    include_once("../../Clases/Conexion.php");
        
    $conn = new Conexion();
    

    $usuarios = "SELECT P.ID_Paciente AS ID,  
                CONCAT(U.Nombre, ' ' ,U.Apellido_P,' ' , U.Apellido_M) AS Nombre
                FROM Paciente P
                LEFT JOIN Usuario U ON P.CURP = U.CURP";
    $stmt = $conn->seleccionar($usuarios);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear cuenta</title>
    <link rel="stylesheet" href="../../css/styles.css">
    

</head>
<body>
    <div class="header">
    <h1>Clínica de especialidad</h1>
    </div>
    

    <div class=" centrar">
        <h2>Modificar datos de un paciente</h2>
        
    </div>
    <form class="crear-cuenta" action="modificarPacienteR.php" method="POST">
            <fieldset>

                <label for="paciente">Seleccione un paciente</label>
                <select name="paciente" required>
                    <option value="" disabled selected required>Seleccione una opcion</option>
                    <?php
                        foreach ($stmt as $row) {
                            echo "<option value= ". $row['ID'] ."> ". $row['Nombre'] . " </option>";
                        }
                    ?>
                </select>

            </fieldset>
            
            <button type="submit" class="boton-confirmar">Confirmar</button>
        </form>

        <div class="logout centrar">
            <a class="border" href="../inicioRecepcionista.php">Regresar al menú principal</a>
        </div>

   
    
</body>
</html>