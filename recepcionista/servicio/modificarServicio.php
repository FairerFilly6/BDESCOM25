

<?php
    session_start();

    if (!isset($_SESSION['email'])) {
        header("Location: ../index.php");
        exit();
    }
    include_once("../../Clases/Conexion.php");
        
    $conn = new Conexion();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
       $idServicio = $_POST['servicio'];
       $consultaServicio = "SELECT * FROM Servicio WHERE ID_Servicio = ?";
       $paramServicio = array($idServicio);

        $resServicio = $conn->seleccionar($consultaServicio,$paramServicio);
        $rowServicio = $resServicio->fetch(PDO::FETCH_ASSOC);
        
        
    }else{
         header('Location: index.php');
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
        <h2>Registro</h2>
        
    </div>
    <form class="crear-cuenta" action="modificacionServicio.php" method="POST">
            <fieldset>
                <legend>Datos del servicio</legend>

                
                <input type="text" name="id" value="<?php echo $rowServicio['ID_Servicio'] ?>" hidden>

                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre"  value="<?php echo $rowServicio['Nombre'] ?>" required>

                <label for="descripcion">Descripcion</label>
                <input type="text" name="descripcion" value="<?php echo $rowServicio['Descripcion'] ?>" required>

                <label for="costo">Costo</label>
                <input type="number" min=0 name="costo" value="<?php echo $rowServicio['Costo'] ?>" >

                
            <button type="submit" class="boton-confirmar">Confirmar</button>
        </form>
        <div class="logout centrar">
            <a class="border" href="../inicioRecepcionista.php">Regresar al menú principal</a>
        </div>

   
    
</body>
</html>