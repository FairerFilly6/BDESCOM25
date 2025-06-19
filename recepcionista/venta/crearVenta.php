

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
        $concepto = $_POST['concepto'];

        if ($_POST['accion'] === 'add_medicamento') {
            $id = $_POST['medicamento'];
            $cantidad = $_POST['cantidad_med'];

            if (!empty($id) && is_numeric($cantidad) && $cantidad > 0 || $id = "") {
                
                $_SESSION['carrito']['medicamentos'][] = [
                    'id' => $id,
                    'cantidad' => $cantidad
                ];
            } else {
                echo "<script>alert('Por favor selecciona un medicamento y una cantidad válida.');</script>";
            }
        }
        if ($_POST['accion'] === 'add_servicio') {
             $id = $_POST['servicio'];
            $cantidad = $_POST['cantidad_srv'];

            if (!empty($id) && is_numeric($cantidad) && $cantidad > 0 || $id = "") {
               
                $_SESSION['carrito']['servicios'][] = [
                    'id' => $id,
                    'cantidad' => $cantidad
                ];
            } else {
                echo "<script>alert('Por favor selecciona un servicio y una cantidad válida.');</script>";
            }
        }
        if ($_POST['accion'] === 'vaciar') {
            $_SESSION['carrito'] = [
            'medicamentos' => [],
            'servicios' => []
        ];
        }

        if ($_POST['accion'] === 'confirmar') {
         
            if (empty($_SESSION['carrito']['medicamentos']) && empty($_SESSION['carrito']['servicios']) ) {
                echo "<script>alert('No es posible crear ventas vacias');</script>";
            }else{
                //Se obtiene el id del recepcionista para poder insertarlo en venta
                $consultaIDR = "SELECT R.ID_Recepcionista AS ID FROM Recepcionista R
                                LEFT JOIN Empleado E ON R.ID_Empleado = E.ID_Empleado
                                LEFT JOIN Usuario U ON E.CURP = U.CURP WHERE U.Email= ?";
                $paramConsultaIDR = array($_SESSION['email']);
                $resConsultaIDR = $conn->seleccionar($consultaIDR,$paramConsultaIDR);
                $rowConsultaIDR = $resConsultaIDR->fetch(PDO::FETCH_ASSOC); 
                $idRecepcionista =$rowConsultaIDR['ID'];

                // se crea la venta
                $insercionVenta = "INSERT INTO Venta VALUES(?,GETDATE(),?)";
                $paramsVenta = array($idRecepcionista,$concepto);

                $exitoVenta = $conn->insertar($insercionVenta, $paramsVenta);

                if($exitoVenta){
                    //si la venta procede obtenemos el id de esta
                    $idVenta = $conn->lastInsertId();
                    

                    
                    //iteramos sobre el carrito en medicamentos para insertarlo en detalle medicamento
                    foreach ($_SESSION['carrito']['medicamentos'] as $ventaMed) {
                        
                        $insercionDetalleMed = "INSERT INTO DetalleMedicamento VALUES (?,?,?)";
                        $paramDetalleMed = array($idVenta, $ventaMed['id'],$ventaMed['cantidad']);
                        $exitoInsercionDetalleMed = $conn->insertar($insercionDetalleMed, $paramDetalleMed);
                        if (!$exitoInsercionDetalleMed) {
                            exit;
                        }
                    }
                    
                    //iteramos sobre el carrito en servicios para insertarlo en detalle servicio
                    foreach ($_SESSION['carrito']['servicios'] as $ventaSrv) {
                        
                        $insercionDetalleSrv = "INSERT INTO DetalleServicio VALUES (?,?,?)";
                        $paramDetalleSrv = array($idVenta, $ventaSrv['cantidad'],$ventaSrv['id']);
                        $exitoInsercionDetalleSrv = $conn->insertar($insercionDetalleSrv, $paramDetalleSrv);
                        if (!$exitoInsercionDetalleSrv) {
                            exit;
                        }
                    }

                    //insertamos el pago de esta venta
                    
                    $insercionPago = "INSERT INTO Pago 
                    VALUES (1,?,'Tarjeta de debito',GETDATE(), dbo.obtenerTotalVenta (?),'Exito')";
                    $paramPago = array($idVenta,$idVenta);
                    $exitoInsercionPago = $conn->insertar($insercionPago,$paramPago);

                    if (!$exitoInsercionPago) {
                        exit;
                    }
                    
                }
                
            
            }
            
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
        <input type="text" name="concepto" value = "<?php if(isset($_POST['concepto'])){echo $concepto; } 
        ?>" required>

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

            <button class="boton-confirmar" type="submit" name="accion" value="add_medicamento">
                Añadir Medicamento al carrito
            </button>

           


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
            

             <button class="boton-confirmar" type="submit" name="accion" value="add_servicio">
                Añadir Servicio al carrito
            </button>
            

        </fieldset>

        <button type="submit" name="accion" value="vaciar" class="boton-confirmar">Vaciar carrito</button>

        <button type="submit" name="accion" value="confirmar" class="boton-confirmar">Confirmar venta</button>
     </form>

      <h2 class="centrar">Carrito</h2>
     <table class="tabla-consultas">
            <thead>
                <th>ID</th>
                <th>Producto</th>
               
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Importe</th>
            </thead>
            <tbody>
                <?php
                    foreach ($_SESSION['carrito']['medicamentos'] as $med) {
                        // Obtener nombre y precio del medicamento desde $resMedicamentos
                        $medicamentos = "SELECT * FROM Medicamento WHERE Caducidad > GETDATE() ";
                        $resMedicamentos = $conn->seleccionar($medicamentos);
                        foreach ($resMedicamentos as $m) {
                            if ($m['ID_Medicamento'] == $med['id']) {
                                $nombre = $m['Nombre'];
                                $precio = (float)$m['Precio'];
                                $cantidad = (int)$med['cantidad'];
                                $importe = $precio * $cantidad;
                                echo "<tr>
                                        <td> ". $med['id'] . "</td>
                                        <td>".$nombre . "</td>
                                        <td>$".$precio . "</td>
                                        <td>".$med['cantidad'] . "</td>
                                        <td>$".$importe . "</td>
                                    </tr>";
                            }
                        }
                    }

                    foreach ($_SESSION['carrito']['servicios'] as $srv) {
                        $servicios = "SELECT * FROM Servicio";
                        $resServicios =  $conn->seleccionar($servicios);
                        foreach ($resServicios as $s) {
                            if ($s['ID_Servicio'] == $srv['id']) {
                                $nombre = $s['Nombre'];
                                $precio = (float)$s['Costo'];
                                $cantidad = (int)$srv['cantidad'];
                                $importe = $precio * $cantidad;
                                echo "<tr>
                                        <td>". $srv['id']. "</td>
                                        <td>". $nombre. "</td>
                                        <td>$". $precio. "</td>
                                        <td>". $srv['cantidad']. "</td>
                                        <td>$". $importe. "</td>
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