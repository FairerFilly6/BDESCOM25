

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
        $lote = $_POST['lote'];
        $costo = $_POST['costo'];
        $precio = $_POST['precio'];
        $caducidad = $_POST['caducidad'];

        
        $insercionMedicamento = "INSERT INTO Medicamento VALUES (?,?,?,?,?)";
        $paramMedicamento = array($nombre, $lote, $costo, $precio, $caducidad);

        $exitoInsercion = $conn->insertar($insercionMedicamento, $paramMedicamento);

        if($exitoInsercion){
            header("Location: mostrarMedicamentos.php");
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
        <h2>Registrar un Medicamento</h2>
        
    </div>
    <form class="crear-cuenta" action="crearMedicamento.php" method="POST">
            <fieldset>
                <legend>Información del Medicamento</legend>

                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" required>

                <label for="lote">Lote:</label>
                <input type="text" name="lote" required>

                <label for="costo">Costo:</label>
                <input type="number" min=0 name="costo" required>

                <label for="precio">Precio:</label>
                <input type="number" min=0 name="precio" required>

                <label for="caducidad">Caducidad:</label>
                <input type="date" min=0 name="caducidad" required>

                
            </fieldset>

            
            
            <button type="submit" class="boton-confirmar">Confirmar</button>
        </form>
        <div class="logout centrar">
            <a class="border" href="../inicioRecepcionista.php">Regresar al menú principal</a>
        </div>

   
    
</body>
</html>