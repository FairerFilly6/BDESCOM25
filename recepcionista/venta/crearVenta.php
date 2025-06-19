

<?php
    session_start();

    if (!isset($_SESSION['email'])) {
        header("Location: ../index.php");
        exit();
    }

    if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [
        'medicamentos' => [],
        'servicios' => []
    ];
}

    include_once("../../Clases/Conexion.php");
        
    $conn = new Conexion();
    $medicamentos = "SELECT * FROM Medicamento WHERE Caducidad > GETDATE() ";
    $resMedicamentos = $conn->seleccionar($medicamentos);

    $servicios = "SELECT * FROM Servicio";
    $resServicios =  $conn->seleccionar($servicios);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if ($_POST['accion'] === 'add_medicamento') {
            $_SESSION['carrito']['medicamentos'][] = [
                'id' => $_POST['medicamento'],
                'cantidad' => $_POST['cantidad_med']
            ];
        }

        if ($_POST['accion'] === 'add_servicio') {
            $_SESSION['carrito']['servicios'][] = [
                'id' => $_POST['servicio'],
                'cantidad' => $_POST['cantidad_srv']
            ];
        }

        if ($_POST['accion'] === 'confirmar') {
            $concepto = $_POST['concepto'];
            $idRecepcionista = $_SESSION['id_recepcionista']; // asumiendo que está en la sesión

            $queryVenta = "INSERT INTO Venta (ID_Recepcionista, FechaVenta, Concepto) VALUES (?, GETDATE(), ?)";
            $conn->insertar($queryVenta, [$idRecepcionista, $concepto]);

            // obtener ID de la última venta
            $idVenta = $conn->lastInsertId(); // Necesitas implementar esto

            // insertar medicamentos
            foreach ($_SESSION['carrito']['medicamentos'] as $med) {
                $conn->insertar("INSERT INTO DetalleMedicamento VALUES (?, ?, ?)", [$idVenta, $med['id'], $med['cantidad']]);
            }

            // insertar servicios
            foreach ($_SESSION['carrito']['servicios'] as $srv) {
                $conn->insertar("INSERT INTO DetalleServicio VALUES (?, ?, ?)", [$idVenta, $srv['cantidad'], $srv['id']]);
            }

            // limpiar carrito
            $_SESSION['carrito'] = ['medicamentos' => [], 'servicios' => []];

            header("Location: /recepcionista/inicioRecepcionista.php");
            exit();
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
        <h2>Nueva venta</h2>
        
    </div>
    <form class="crear-cuenta" action="crearVenta.php" method="POST">

        <label for="concepto">Concepto</label>
        <input type="text" name="concepto" required>

        <fieldset>
            <legend>Medicamentos</legend>

            <label for="">Medicamentos </label>
            <select name="medicamento" id="medicamento">
                <option value="" disabled selected>Seleccione un medicamento</option>
                <?php 
                foreach ($resMedicamentos as $row) {
                    echo '<option value ="'.$row['ID_Medicamento'] .'" >';
                    echo $row['Nombre'] . ' - Costo: $' . $row['Precio']  ;
                    echo '</option>';
                }
                ?>
            </select>

            <label for="cantidad">Cantidad</label>
            <input type="number" name="cantidad_med" min="0" step="1" >

            <button class="boton-confirmar" type="submit" name="accion" value="add_medicamento">Añadir Medicamento al carrito</button>

           


        </fieldset>

        <fieldset>
            <legend>Servicios</legend>

            <label for="">Servicios </label>
            <select name="servicio" id="servicio">
                <option value="" disabled selected>Seleccione un servicio</option>
                <?php 
                foreach ($resServicios as $row) {
                    echo '<option value ="'.$row['ID_Servicio'] .'" >';
                    echo $row['Nombre'] . ' - Costo: $' . $row['Costo']  ;
                    echo '</option>';
                }
                ?>

            </select>

            <label for="cantidad">Cantidad</label>
            <input type="number" name="cantidad_srv" min="0" step="1" >
            

             <button class="boton-confirmar" type="submit" name="accion" value="add_servicio">Añadir Servicio al carrito</button>
            

        </fieldset>


       
        



            
            
        <button type="submit" name="accion" value="confirmar" class="boton-confirmar">Confirmar</button>
     </form>

      <h2 class="centrar">Carrito</h2>
     <table class="tabla-consultas">
            <thead>
                <th>ID</th>
                <th>Producto</th>
                <th>Descripcion</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Importe</th>
            </thead>
            <tbody>
                <?php
                    foreach ($_SESSION['carrito']['medicamentos'] as $med) {
                        // Obtener nombre y precio del medicamento desde $resMedicamentos
                        foreach ($resMedicamentos as $m) {
                            if ($m['ID_Medicamento'] == $med['id']) {
                                $nombre = $m['Nombre'];
                                $precio = $m['Precio'];
                                $importe = $precio * $med['cantidad'];
                                echo "<tr>
                                        <td>{$med['id']}</td>
                                        <td>Medicamento</td>
                                        <td>{$nombre}</td>
                                        <td>\${$precio}</td>
                                        <td>{$med['cantidad']}</td>
                                        <td>\${$importe}</td>
                                    </tr>";
                            }
                        }
                    }

                    foreach ($_SESSION['carrito']['servicios'] as $srv) {
                        foreach ($resServicios as $s) {
                            if ($s['ID_Servicio'] == $srv['id']) {
                                $nombre = $s['Nombre'];
                                $precio = $s['Costo'];
                                $importe = $precio * $srv['cantidad'];
                                echo "<tr>
                                        <td>{$srv['id']}</td>
                                        <td>Servicio</td>
                                        <td>{$nombre}</td>
                                        <td>\${$precio}</td>
                                        <td>{$srv['cantidad']}</td>
                                        <td>\${$importe}</td>
                                    </tr>";
                            }
                        }
                    }
                    ?>
            
            </tbody>
            

        </table>
        <div class="logout centrar">
            <a class="border" href="../inicioRecepcionista.php">Regresar al menú principal</a>
        </div>

   
    
</body>
</html>