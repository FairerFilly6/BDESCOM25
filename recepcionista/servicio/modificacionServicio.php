<?php
     session_start();

    if (!isset($_SESSION['email'])) {
        header("Location: ../index.php");
        exit();
    }

    include_once("../../Clases/Conexion.php");
        
    $conn = new Conexion();

    if (!empty($_POST)){
        $idServicio = $_POST['id'];
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $costo = $_POST['costo'];

        $insercionSrv = "UPDATE Servicio 
                        SET Nombre = ?,
                        Descripcion = ?,
                        Costo = ?
                        WHERE ID_Servicio = ?";

        $paramInsercionSrv = array($nombre,$descripcion,$costo,$idServicio);

        try {
            $exitoActualizacion = $conn->insertar($insercionSrv, $paramInsercionSrv);
        } catch (Exception $e) {
            echo "Error al ejecutar actualización: " . $e->getMessage();
        }

        
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ClinicaDeEspecialidad</title>
    <link rel="stylesheet" href="../../css/styles.css">

</head>
<body>
    <div class="header">
        <h1>Clínica de especialidad</h1>
    </div>

     <?php if (!($exitoActualizacion)): ?>
        <h3 class="alerta rojo"> No se pudieron realizar los cambios</h3>
    <?php elseif($exitoActualizacion) :?>
        <h3 class="alerta verde"> Cambios realizados</h3>
    <?php endif;?>
    
    <div class="logout centrar">
        <a class="border" href="../inicioRecepcionista.php">Regresar al menú principal</a>
    </div>

</body>
</html>