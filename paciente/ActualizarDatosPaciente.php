<?php
session_start();
if (!isset($_SESSION['email'])) header('Location: ../index.php');
require_once '../Clases/Conexion.php';
$conn = new Conexion();

// Traer datos actuales
$userStmt = $conn->seleccionar(
    "SELECT Nombre, Email FROM Usuario WHERE Email=:email",
    [':email'=>$_SESSION['email']]
);
$usuario = $userStmt->fetch(PDO::FETCH_ASSOC);

$pacStmt = $conn->seleccionar("
    SELECT Estatura,Peso,Tipo_Sangre,Alergia,Padecimientos
      FROM Paciente
     WHERE CURP=(SELECT CURP FROM Usuario WHERE Email=:email)
", [':email'=>$_SESSION['email']]);
$paciente = $pacStmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Actualizar Datos</title></head>
<body>
  <h2>Actualizar perfil</h2>
  <form action="procesarActualizacion.php" method="post">
    <label>Nombre:</label>
    <input type="text" name="nombre" value="<?= htmlspecialchars($usuario['Nombre']) ?>" required><br>

    <label>Email:</label>
    <input type="email" name="email" value="<?= htmlspecialchars($usuario['Email']) ?>" required><br>

    <label>Contraseña (nueva):</label>
    <input type="password" name="pass" placeholder="Dejar en blanco si no cambia"><br>

    <label>Estatura (m):</label>
    <input type="number" name="estatura" step="0.01" value="<?= $paciente['Estatura'] ?>"><br>

    <label>Peso (kg):</label>
    <input type="number" name="peso" step="0.01" value="<?= $paciente['Peso'] ?>"><br>

    <label>Tipo de sangre:</label>
    <input type="text" name="tipo_sangre" value="<?= htmlspecialchars($paciente['Tipo_Sangre']) ?>"><br>

    <label>Alergias:</label>
    <textarea name="alergias"><?= htmlspecialchars($paciente['Alergia']) ?></textarea><br>

    <label>Padecimientos:</label>
    <textarea name="padecimientos"><?= htmlspecialchars($paciente['Padecimientos']) ?></textarea><br>

    <button type="submit">Actualizar datos</button>
  </form>
  <p><a href="inicioPaciente.php">← Volver al menú</a></p>
</body>
</html>
