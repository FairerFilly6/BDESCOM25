<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../index.php");
    exit();
}

include_once("../Clases/Conexion.php");

// Inicializar sesión de receta si no existe
if (!isset($_SESSION['detalleReceta'])) {
    $_SESSION['detalleReceta'] = [
        'medicamentos' => [],
    ];
}

$conn = new Conexion();

// Obtener medicamentos válidos
$medicamentos = "SELECT * FROM Medicamento WHERE Caducidad > GETDATE()";
$resMedicamentos = $conn->seleccionar($medicamentos);

// Obtener idCita al inicio (por POST o ya en sesión)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idCita'])) {
    $idCita = $_POST['idCita'];
    if(isset($_POST['idCita']) && isset($_POST['dx']) && isset($_POST['tratamiento']) ){
        
        $observaciones = $_POST['observaciones'];
        $dx = $_POST['dx'];
        $tratamiento = $_POST['tratamiento'];
    }
    
    $_SESSION['detalleReceta']['idCita'] = $idCita;

    if (isset($_POST['accion']) && $_POST['accion'] === 'add_medicamento') {
        $idMedicamento = $_POST['medicamento'];
        $_SESSION['detalleReceta']['medicamentos'][] = [
            'id' => $idMedicamento
        ];
    }

    if (isset($_POST['accion']) && $_POST['accion'] === 'vaciar') {
        $_SESSION['detalleReceta']['medicamentos'] = [];
    }

    if (isset($_POST['accion']) && $_POST['accion'] === 'crearReceta') {
        
        
        $creacionReceta = "INSERT INTO Receta (Fecha,Observaciones,Diagnostico,Tratamiento,Folio_Cita) VALUES (GETDATE(),?,?,?,?)";
        $paramCreacionReceta = array($observaciones,$dx,$tratamiento,$idCita);

        $exitoReceta = $conn->insertar($creacionReceta,$paramCreacionReceta);


        if ($exitoReceta) {
            $idReceta = $conn->lastInsertId();
            foreach ($_SESSION['detalleReceta']['medicamentos'] as $med) {
                $insercionDetalleReceta = "INSERT INTO DetalleReceta VALUES (?,?)";
                $paramDetalleReceta = array($idReceta,$med['id']);
                $exitoDetalleReceta = $conn->insertar($insercionDetalleReceta,$paramDetalleReceta);

                $citaAtendida = "UPDATE Cita
                                SET ID_EstatusCita = 6
                                WHERE Folio_Cita = ?";
                $paramCitaAtendida = array($idCita);

                $exitoCitaAtendida = $conn->modificar($citaAtendida,$paramCitaAtendida);
                
                if ($exitoDetalleReceta && $exitoCitaAtendida) {
                    echo "todo bie vaya";
                }else{
                    echo "algo fallo";
                }
            }
        }
    }

} elseif (isset($_SESSION['detalleReceta']['idCita'])) {
    $idCita = $_SESSION['detalleReceta']['idCita'];
} else {
    die("No se especificó una cita válida.");
}

// Añadir medicamento al detalle

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Clínica de especialidad</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="header">
        <h1>Clínica de especialidad</h1>
    </div>

    <div class="centrar">
        <h2>Crear receta para la cita</h2>
    </div>

    <div class="crear-cuenta">
        <!-- FORMULARIO PRINCIPAL -->
        <form method="POST" action="procesarReceta.php">
            <fieldset>
                <legend>Datos de la cita</legend>

                <label for="observaciones">Observaciones:</label><br>
                <textarea name="observaciones" rows="3" required><?php if (isset($_POST['observaciones'])) {
                    echo $_POST['observaciones'];
                }?></textarea><br>

                <label for="dx">Diagnóstico:</label><br>
                <textarea name="dx" rows="3" required><?php if (isset($_POST['dx'])) {
                    echo $_POST['dx'];
                }?></textarea><br>

                <label for="tratamiento">Tratamiento:</label><br>
                <textarea name="tratamiento" rows="3" required><?php if (isset($_POST['tratamiento'])) {
                    echo $_POST['tratamiento'];
                }?></textarea>

                <!-- ID de la cita -->
                <input type="hidden" name="idCita" value="<?php echo $idCita; ?>">
            </fieldset>

            <fieldset>
                <legend>Añadir medicamentos a la receta</legend>
                <select name="medicamento" required>
                    <option value="" disabled selected>Seleccione un medicamento</option>
                    <?php 
                    foreach ($resMedicamentos as $row) {
                        echo '<option value="' . $row['ID_Medicamento'] . '">';
                        echo $row['Nombre'] . ' - Costo: $' . $row['Precio'];
                        echo '</option>';
                    }
                    ?>
                </select>

                <!-- Botón para añadir medicamento -->
                <button type="submit" name="accion" value="add_medicamento" class="boton-confirmar">
                    Añadir medicamento a la receta
                </button>
                <button type="submit" name="accion" value="vaciar" class="boton-confirmar">
                    Vaciar medicamentos de la receta
                </button>
            </fieldset>

            <!-- Botón para crear la receta final -->
            <button type="submit" class="boton-confirmar" name="accion" value="crearReceta">Crear receta</button>
        </form>

        <!-- TABLA DE MEDICAMENTOS -->
        <h2>Medicamentos en la receta</h2>
        <table class="tabla-consultas">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Lote</th>
                    <th>Precio</th>
                    <th>Caducidad</th>
                </tr>
            </thead>
            <tbody>
                <?php
               
                foreach ($_SESSION['detalleReceta']['medicamentos'] as $med) {
                    
                    $medicamentos = "SELECT * FROM Medicamento WHERE Caducidad > GETDATE()";
                    $resMedicamentos = $conn->seleccionar($medicamentos);
                    foreach ($resMedicamentos as $m) {
                        if ($m['ID_Medicamento'] == $med['id']) {
                            echo "<tr>
                                    <td>{$m['ID_Medicamento']}</td>
                                    <td>{$m['Nombre']}</td>
                                    <td>{$m['Lote']}</td>
                                    <td>\${$m['Precio']}</td>
                                    <td>{$m['Caducidad']}</td>
                                </tr>";
                        }
                    }
                }
                ?>
            </tbody>
        </table>

        <div class="logout centrar">
            <a class="border" href="../inicioDoctor.php">Regresar al menú principal</a>
        </div>
    </div>
</body>
</html>

