
<?php
    session_start();

    if (!isset($_SESSION['email'])) {
        header("Location: ../index.php");
        exit();
    }

    include_once("../../Clases/Conexion.php");
    $conn = new Conexion();


    $sqlEspecialidad = "select * from Especialidad";
    $stmtEspecialidades= $conn->seleccionar($sqlEspecialidad);

    if ( !empty($_POST) ) {

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
        $email = $_POST['Email'];
        $pwd = $_POST['Pwd'];
        $cedPro = $_POST['ced_pro'];
        $especialidad= $_POST['especialidad'];

        $sqlProcedure = ' exec SP_ALTA_MEDICO ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?';
        $paramAlta =
        array($curp,$nombre,$apPat,$apMat,$fechaNac,$calle,$numero,$colonia,$cp,$ciudad,$estado,$telefono,$email,$pwd,$cedPro,$especialidad);
        $exitoUsuario = $conn->insertar($sqlProcedure,$paramAlta);
        if ($exitoUsuario) {
            //me da info de la variable     var_dump($exitoUsuario);
        }
    } else {

    }


    //$resConsulta = $conn->seleccionar($sql);
    
    
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
    <form class="crear-cuenta" action="altaDoctor.php" method="POST">
            <fieldset>
                <legend>Datos personales</legend>

                <label for="curp">CURP:</label>
                <input type="text" name="curp" required>

                <label for="nombre">Nombre:</label>
                <input type="text" name="Nombre" required>

                <label for="apellido_p">Apellido paterno:</label>
                <input type="text" name="Apellido_P" required>

                <label for="apellido_m">Apellido materno:</label>
                <input type="text" name="Apellido_M" >

                <label for="fecha_nac">Fecha de nacimiento:</label>
                <input type="date" name="FechaNac" required>

                <label for="calle">Calle:</label>
                <input type="text" name="Calle" >

                <label for="numero">Número:</label>
                <input type="text" name="Numero" >

                <label for="colonia">Colonia:</label>
                <input type="text" name="Colonia" required>

                <label for="codigo_p">Código Postal:</label>
                <input type="text" name="Codigo_P" >

                <label for="ciudad">Ciudad:</label>
                <input type="text" name="Ciudad" required>

                <label for="estado">Estado:</label>
                <input type="text" name="Estado" >

                <label for="telefono">Teléfono:</label>
                <input type="tel" name="Telefono" required>

                <label for="email">Correo electrónico:</label>
                <input type="email" name="Email" required>

                <label for="pwd">Contraseña:</label>
                <input type="password" name="Pwd" required>

                <label for="ced_pro">Cedula Profesional</label>
                <input type="text" name="ced_pro" required>

                <label for="ced_pro">Especialidad</label>
                <select name="especialidad" id="especialidad" required>
                    <option value="" disabled selected >Seleccione una especialidad</option>
                    <?php
                        if($stmtEspecialidades){
                            foreach ($stmtEspecialidades as $row) {
                                echo '<option value ="'.$row['ID_Especialidad'] .'" >';
                                echo $row['Nombre']  ;
                                echo '</option>';
                            }     
                        }
                    ?>
                </select>
            </fieldset>

            
            <button type="submit" class="boton-confirmar">Confirmar</button>
    </form>

        <div class="logout centrar">
            <a class="border" href="../inicioRecepcionista.php">Regresar al menú principal</a>
        </div>

   
    
</body>
</html>