
<?php
session_start();
include_once(__DIR__."/Clases/Conexion.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['usuario'];
    $pwd = $_POST['contrasena'];

    $conn = new Conexion();
    $sql = "SELECT Email,Pwd,ID_TipoUsuario AS id, CURP FROM Usuario WHERE Email = ?";
    $params = array($email);

    $stmt=$conn->seleccionar($sql,$params);

    if ($stmt) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row && $row['Pwd'] == $pwd) {
            $_SESSION['email'] = $email;
            if ($row['id'] == 1) {
                header("Location: paciente/inicioPaciente.php");
                exit();
            } else {
                $consulta = "SELECT ID_TipoEmpleado FROM Empleado WHERE CURP = ?";
                $stmtConsulta = $conn->seleccionar($consulta, [$row['CURP']]);
                if ($stmtConsulta) {
                    $rowCons = $stmtConsulta->fetch(PDO::FETCH_ASSOC);
                    if ($rowCons) {
                        if ($rowCons['ID_TipoEmpleado'] == 1) {
                            header("Location: doctor/inicioDoctor.php");
                            exit();
                        } elseif ($rowCons['ID_TipoEmpleado'] == 2) {
                            header("Location: recepcionista/inicioRecepcionista.php");
                            exit();
                        }
                    }else{
                        header("Location: index.php");
                    }
                }
            }
        }
    }
    
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login Clínica</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<div class="header">
    <h1>Clínica de especialidad</h1>
</div>

<div class="login" >
    <h2>Iniciar sesión</h2>


    <form method="POST" class="border">
        <label>Usuario:</label><br>
        <input type="text" name="usuario" required><br><br>

        <label>Contraseña:</label><br>
        <input type="password" name="contrasena" required><br><br>

        <button type="submit">Entrar</button>
    </form>
</div>
<div class="signin ">
    <a class="border" href="crearCuenta.php">Crear cuenta</a>
</div>

</body>
</html>
