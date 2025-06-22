<?php
     session_start();

    if (!isset($_SESSION['email'])) {
        header("Location: ../index.php");
        exit();
    }

    include_once("../../Clases/Conexion.php");
        
    $conn = new Conexion();

    if (!empty($_POST)){
        $idMedicamento = $_POST['id'];
        $nombre = $_POST['nombre'];
        $lote = $_POST['lote'];
        $costo = $_POST['costo'];
        $precio = $_POST['precio'];
        $caducidad = $_POST['caducidad'];

        $insercionMed = "UPDATE Medicamento 
                        SET Nombre = ?,
                        Lote = ?,
                        Costo = ?,
                        Precio = ?,
                        Caducidad = ?
                        WHERE ID_Medicamento = ?";

        $paramInsercionMed = array($nombre,$lote,$costo,$precio, $caducidad ,$idMedicamento);

        try {
            $exitoActualizacion = $conn->insertar($insercionMed, $paramInsercionMed);
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