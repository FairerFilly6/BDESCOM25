

<?php
    session_start();

    if (!isset($_SESSION['email'])) {
        header("Location: ../index.php");
        exit();
    }
    include_once("../../Clases/Conexion.php");
        
    $conn = new Conexion();

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['paciente'])) {
        $idPaciente = $_POST['paciente'];
        $sql = "SELECT 
                    U.CURP, U.Nombre, U.Apellido_P, U.Apellido_M, U.Fecha_Nac, U.Calle,
                    U.Numero, U.Colonia, U.Codig_P, U.Ciudad, U.Estado, U.Telefono, 
                    U.Email, U.Pwd,
                    P.Estatura, P.Peso, P.Tipo_Sangre, P.Alergia, P.Padecimientos
                FROM Paciente P
                INNER JOIN Usuario U ON P.CURP = U.CURP
                WHERE P.ID_Paciente = ? ";
        $params = array($idPaciente);
        $stmt = $conn->seleccionar($sql,$params);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        $tipoSangre = isset($resultado['Tipo_Sangre']) ? $resultado['Tipo_Sangre'] : '';

        
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
    <form class="crear-cuenta" action="modificacionPacienteR.php" method="POST">
            <fieldset>
                <legend>Datos personales</legend>

                
                <input type="text" name="curp" value="<?php echo $resultado['CURP'] ?>" hidden>

                <label for="nombre">Nombre:</label>
                <input type="text" name="Nombre"  value="<?php echo $resultado['Nombre'] ?>" required>

                <label for="apellido_p">Apellido paterno:</label>
                <input type="text" name="Apellido_P" value="<?php echo $resultado['Apellido_P'] ?>" required>

                <label for="apellido_m">Apellido materno:</label>
                <input type="text" name="Apellido_M" value="<?php echo $resultado['Apellido_M'] ?>" >

                <label for="fecha_nac">Fecha de nacimiento:</label>
                <input type="date" name="FechaNac" required value="<?php echo $resultado['Fecha_Nac'] ?>"  required>

                <label for="calle">Calle:</label>
                <input type="text" name="Calle" value="<?php echo $resultado['Calle'] ?>" >

                <label for="numero">Número:</label>
                <input type="text" name="Numero" value="<?php echo $resultado['Numero'] ?>" >

                <label for="colonia">Colonia:</label>
                <input type="text" name="Colonia" required value="<?php echo $resultado['Colonia'] ?>" >

                <label for="codigo_p">Código Postal:</label>
                <input type="text" name="Codigo_P" value="<?php echo $resultado['Codig_P'] ?>" >

                <label for="ciudad">Ciudad:</label>
                <input type="text" name="Ciudad" required  value="<?php echo $resultado['Ciudad'] ?>" >

                <label for="estado">Estado:</label>
                <input type="text" name="Estado" value="<?php echo $resultado['Estado'] ?>" >

                <label for="telefono">Teléfono:</label>
                <input type="tel" name="Telefono" required value="<?php echo $resultado['Telefono'] ?>">

                <label for="pwd">Contraseña:</label>
                <input type="text" name="Pwd" value="<?php echo $resultado['Pwd'] ?>" required>
            </fieldset>

            <fieldset>
                <legend>Información médica</legend>

                <label for="estatura">Estatura (en cm):</label>
                <input type="number" name="Estatura" min="0" step="0.01"  value="<?php echo $resultado['Estatura'] ?>" required>

                <label for="peso">Peso (en kg):</label>
                <input type="number" name="Peso" min="0" step="0.01" value="<?php echo $resultado['Peso'] ?>" required>

                <label for="tipo_sangre">Tipo de sangre:</label>
                <select name="Tipo_Sangre" required>
                    <option disabled <?php echo ($tipoSangre == '') ? 'selected' : ''; ?>>Seleccione</option>
                    <option value="A+" <?php echo ($tipoSangre == 'A+') ? 'selected' : ''; ?>>A+</option>
                    <option value="A-" <?php echo ($tipoSangre == 'A-') ? 'selected' : ''; ?>>A-</option>
                    <option value="B+" <?php echo ($tipoSangre == 'B+') ? 'selected' : ''; ?>>B+</option>
                    <option value="B-" <?php echo ($tipoSangre == 'B-') ? 'selected' : ''; ?>>B-</option>
                    <option value="O+" <?php echo ($tipoSangre == 'O+') ? 'selected' : ''; ?>>O+</option>
                    <option value="O-" <?php echo ($tipoSangre == 'O-') ? 'selected' : ''; ?>>O-</option>
                    <option value="AB+" <?php echo ($tipoSangre == 'AB+') ? 'selected' : ''; ?>>AB+</option>
                    <option value="AB-" <?php echo ($tipoSangre == 'AB-') ? 'selected' : ''; ?>>AB-</option>
                </select>


                <label for="alergia">Alergias:</label>
                <textarea name="Alergia" rows="3" ><?php echo $resultado['Alergia'] ?></textarea>

                <label for="padecimientos">Padecimientos:</label>
                <textarea name="Padecimientos" rows="3"><?php echo $resultado['Padecimientos'] ?></textarea>
            </fieldset>
            
            <button type="submit" class="boton-confirmar">Confirmar</button>
        </form>
        <div class="logout centrar">
            <a class="border" href="../inicioRecepcionista.php">Regresar al menú principal</a>
        </div>

   
    
</body>
</html>