
<?php
session_start();
include_once(__DIR__."/Clases/Conexion.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['usuario'];
    $pwd = $_POST['contrasena'];

    $conn = new Conexion();
    $sql = "SELECT Email,password,ID_TipoUsuario AS id, CURP FROM Usuario WHERE Email = ?";
    $params = array($email);

    $stmt=$conn->seleccionar($sql,$params);

    if($stmt){
        foreach($stmt as $row){
            if($row['password'] == $pwd){
                $_SESSION['email']=$email;
                if ($row['id'] == 1) {
                    header("Location: inicioPaciente.php");
                }else{
                    $consulta = "SELECT ID_TipoEmpleado FROM Empleado WHere CURP = ?";
                    $parametrosConsulta = array($row['CURP']);
                    $stmtConsulta = $conn->seleccionar($consulta,$parametrosConsulta);
                    if($stmtConsulta){
                        foreach($stmtConsulta as $rowCons){
                            if($rowCons['ID_TipoEmpleado'] == 1){
                                header("Location: inicioDoctor.php");
                            }elseif($rowCons['ID_TipoEmpleado'] == 2){
                                header("Location: inicioRecepcionista.php");
                            }
                        }
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
    <a class="border" href="#">Crear cuenta</a>
</div>

</body>
</html>
