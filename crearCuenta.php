

<?php
    include_once("Clases/Conexion.php");
        
    $conn = new Conexion();
    if(!empty($_POST)){

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
        $estatura = $_POST['Estatura'];
        $peso = $_POST['Peso'];
        $tipoSangre = $_POST['Tipo_Sangre'];
        $alergia = $_POST['Alergia'];
        $padecimientos = $_POST['Padecimientos'];

        $insercionUsuarios = "INSERT INTO Usuario VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $paramUsuarios = array($curp,$nombre, $apPat, $apMat, $fechaNac, $calle,
                            $numero,$colonia,$cp, $ciudad, $estado, $telefono, $email, $pwd,1);

        $insercionPaciente = "INSERT INTO Paciente VALUES (?,?,?,?,?,?)";
        $paramPaciente = array($curp,$estatura,$peso,$tipoSangre,$alergia,$padecimientos);

        $exitoUsuario = $conn->insertar($insercionUsuarios,$paramUsuarios);
        $exitoPaciente = $conn->insertar($insercionPaciente,$paramPaciente);

        if ($exitoPaciente && $exitoUsuario) {
            header('Location: index.php');
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear cuenta</title>
    <link rel="stylesheet" href="css/styles.css">
    

</head>
<body>
    <div class="header">
    <h1>Clínica de especialidad</h1>
    </div>
    

    <div class=" centrar">
        <h2>Registro</h2>
        
    </div>
    <form class="crear-cuenta" action="crearCuenta.php" method="POST">
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
            </fieldset>

            <fieldset>
                <legend>Información médica</legend>

                <label for="estatura">Estatura (en cm):</label>
                <input type="number" name="Estatura" min="0" step="0.01" required>

                <label for="peso">Peso (en kg):</label>
                <input type="number" name="Peso" min="0" step="0.01" required>

                <label for="tipo_sangre">Tipo de sangre:</label>
                <select name="Tipo_Sangre" required>
                    <option disabled selected >Seleccione</option>
                    <option value="A+">A+</option>
                    <option value="A-">A-</option>
                    <option value="B+">B+</option>
                    <option value="B-">B-</option>
                    <option value="O+">O+</option>
                    <option value="O-">O-</option>
                    <option value="AB+">AB+</option>
                    <option value="AB-">AB-</option>
                </select>

                <label for="alergia">Alergias:</label>
                <textarea name="Alergia" rows="3"></textarea>

                <label for="padecimientos">Padecimientos:</label>
                <textarea name="Padecimientos" rows="3"></textarea>
            </fieldset>
            
            <button type="submit" class="boton-confirmar">Confirmar</button>
        </form>

   
    
</body>
</html>