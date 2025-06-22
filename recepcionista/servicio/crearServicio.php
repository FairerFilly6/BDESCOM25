

<?php
    session_start();

    if (!isset($_SESSION['email'])) {
        header("Location: ../index.php");
        exit();
    }
    include_once("../../Clases/Conexion.php");
        
    $conn = new Conexion();
    if(!empty($_POST)){

        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $costo = $_POST['costo'];
        
        $insercionServicio = "INSERT INTO Servicio VALUES (?,?,?)";
        $paramServicio = array($nombre, $descripcion, $costo);

        $exitoInsercion = $conn->insertar($insercionServicio, $paramServicio);

        if($exitoInsercion){
            header("Location: mostrarServicios.php");
        }

    }
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
        <h2>Registrar un servicio</h2>
        
    </div>
    <form class="crear-cuenta" action="crearServicio.php" method="POST">
            <fieldset>
                <legend>Información del servicio</legend>

                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" required>

                <label for="descripcion">Descripcion:</label>
                <input type="text" name="descripcion" required>

                <label for="costo">Costo:</label>
                <input type="number" min=0 name="costo" required>

                
            </fieldset>

            
            
            <button type="submit" class="boton-confirmar">Confirmar</button>
        </form>
        <div class="logout centrar">
            <a class="border" href="../inicioRecepcionista.php">Regresar al menú principal</a>
        </div>

   
    
</body>
</html>