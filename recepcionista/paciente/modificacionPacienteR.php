<?php
     session_start();

    if (!isset($_SESSION['email'])) {
        header("Location: ../index.php");
        exit();
    }

    include_once("../../Clases/Conexion.php");
        
    $conn = new Conexion();

    if (!empty($_POST)){
        $curp = $_POST['curp'];
        $nombre = $_POST['Nombre'];
        $apPat = $_POST['Apellido_P'];
        $apMat = $_POST['Apellido_M'];
        $fechaNac = $_POST['FechaNac'];
        $calle = $_POST['Calle'];
        $numero = $_POST['Numero'];
        $colonia = $_POST['Colonia'];
        $cp = $_POST['Codigo_P'];
        $ciudad = $_POST['Ciudad'];
        $estado = $_POST['Estado'];
        $telefono = $_POST['Telefono'];
        $pwd = $_POST['Pwd'];
        $estatura = $_POST['Estatura'];
        $peso = $_POST['Peso'];
        $tipoSangre = $_POST['Tipo_Sangre'];
        $alergia = $_POST['Alergia'];
        $padecimientos = $_POST['Padecimientos'];

        $modificacionUsuario = "UPDATE Usuario 
                                SET Nombre = ?,
                                Apellido_P = ?,
                                Apellido_M = ?,
                                Fecha_Nac = ?,
                                Calle = ?,
                                Numero = ?,
                                Colonia  = ?,
                                Codig_P = ?,
                                Ciudad = ?,
                                Estado = ?,
                                Telefono = ?,
                                Pwd = ?
                                WHERE CURP = ?
                                ";
        $paramUsuario = array($nombre, $apPat, $apMat, $fechaNac, $calle,
                            $numero,$colonia,$cp, $ciudad, $estado, $telefono, $pwd,$curp);
        $modificacionPaciente  = "UPDATE Paciente SET
                                Estatura = ?,
                                Peso = ?,
                                Tipo_Sangre = ?,
                                Alergia = ?,
                                Padecimientos = ?
                                WHERE 
                                CURP = ?
                                ";
        $paramPaciente = array($estatura,$peso,$tipoSangre,$alergia, $padecimientos,$curp);

        // $exitoUsuario = $conn->modificar($modificacionUsuario,$paramUsuario);
        // $exitoPaciente = $conn->modificar($modificacionPaciente,$paramPaciente);
        try {
            $exitoUsuario = $conn->modificar($modificacionUsuario,$paramUsuario);
            $exitoPaciente = $conn->modificar($modificacionPaciente,$paramPaciente);
        } catch (Exception $e) {
            echo "Error al ejecutar actualización: " . $e->getMessage();
        }

        // var_dump($exitoPaciente) ;
        // var_dump ($exitoUsuario);

        // if ($exitoUsuario && $exitoPaciente) {
        //     echo "no error jaja";
        //     // header('Location: /recepcionista/paciente/mostrarPacienteR.php');
        // }else{
        //     echo "error";
        // }
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

     <?php if (!($exitoPaciente && $exitoUsuario)): ?>
        <h3 class="alerta rojo"> No se pudieron realizar los cambios</h3>
    <?php elseif($exitoPaciente && $exitoUsuario) :?>
        <h3 class="alerta verde"> Cambios realizados</h3>
    <?php endif;?>
    
    <div class="logout centrar">
        <a class="border" href="../inicioRecepcionista.php">Regresar al menú principal</a>
    </div>

</body>
</html>