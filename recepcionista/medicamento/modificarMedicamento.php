

<?php
    session_start();

    if (!isset($_SESSION['email'])) {
        header("Location: ../index.php");
        exit();
    }
    include_once("../../Clases/Conexion.php");
        
    $conn = new Conexion();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
       $idMedicamento = $_POST['medicamento'];
       $consultaServicio = "SELECT * FROM Medicamento WHERE ID_Medicamento = ?";
       $paramMedicamento = array($idMedicamento);

        $resMedicamento = $conn->seleccionar($consultaServicio,$paramMedicamento);
        $rowMedicamento = $resMedicamento->fetch(PDO::FETCH_ASSOC);

        var_dump($rowMedicamento);
        
        
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
    <form class="crear-cuenta" action="modificacionMedicamento.php" method="POST">
            <fieldset>
                <legend>Datos del servicio</legend>

                <input type="text" name="id" value="<?php echo $rowMedicamento['ID_Medicamento'] ?>" hidden>

                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" value="<?php echo $rowMedicamento['Nombre'] ?>"   required>

                <label for="lote">Lote:</label>
                <input type="text" name="lote" value="<?php echo $rowMedicamento['Lote'] ?>"  required>

                <label for="costo">Costo:</label>
                <input type="number" min=0 name="costo" value="<?php echo $rowMedicamento['Costo'] ?>"  required>

                <label for="precio">Precio:</label>
                <input type="number" min=0 name="precio" value="<?php echo $rowMedicamento['Precio'] ?>"  required>

                <label for="caducidad">Caducidad:</label>
                <input type="date" min=0 name="caducidad" value="<?php echo $rowMedicamento['Caducidad'] ?>"  required>


                
            <button type="submit" class="boton-confirmar">Confirmar</button>
        </form>
        <div class="logout centrar">
            <a class="border" href="../inicioRecepcionista.php">Regresar al menú principal</a>
        </div>

   
    
</body>
</html>