<?php
session_start();
if (!isset($_SESSION['email'])) {
    header('Location: ../index.php');
    exit();
}
require_once __DIR__ . '/../Clases/Conexion.php';
$conn = new Conexion();

// Cargar datos de Usuario
$userStmt = $conn->seleccionar(
  "SELECT Nombre, Email FROM Usuario WHERE Email = ?",
  [ $_SESSION['email'] ]
);
$usuario = $userStmt->fetch(PDO::FETCH_ASSOC);

// Cargar datos de Paciente
$pacStmt = $conn->seleccionar(
  "SELECT P.Estatura, P.Peso, P.Tipo_Sangre, P.Alergia, P.Padecimientos
     FROM Paciente P
     JOIN Usuario U ON P.CURP = U.CURP
    WHERE U.Email = ?",
  [ $_SESSION['email'] ]
);
$paciente = $pacStmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Actualizar Datos</title>
  <link rel="stylesheet" href="../css/styles.css">
  <link rel="stylesheet" href="../css/paciente.css">
</head>
<body>
  <div class="header">
    <h1>Clínica de Especialidad</h1>
  </div>

  <div class="menu centrar paciente-container">
    <h2>Actualizar Perfil</h2>

    <?php if (isset($_GET['error'])): ?>
      <div class="alert alert-danger">
        Hubo un error al actualizar tus datos. Inténtalo de nuevo.
      </div>
    <?php endif; ?>

    <form class="paciente-form" action="procesarActualizacion.php" method="post">
      <div class="form-group">
        <label for="nombre">Nombre completo</label>
        <input type="text" id="nombre" name="nombre" class="form-control"
               value="<?= htmlspecialchars($usuario['Nombre']) ?>" required>
      </div>

      <div class="form-group">
        <label for="email">Correo electrónico</label>
        <input type="email" id="email" name="email" class="form-control"
               value="<?= htmlspecialchars($usuario['Email']) ?>" required>
      </div>

      <div class="form-group">
        <label for="pass">Contraseña</label>
        <input type="password" id="pass" name="pass" class="form-control"
               placeholder="Dejar en blanco si no cambia">
      </div>

      <div class="form-group">
        <label for="estatura">Estatura (m)</label>
        <input type="number" id="estatura" name="estatura" class="form-control"
               step="0.01" value="<?= htmlspecialchars($paciente['Estatura']) ?>">
      </div>

      <div class="form-group">
        <label for="peso">Peso (kg)</label>
        <input type="number" id="peso" name="peso" class="form-control"
               step="0.01" value="<?= htmlspecialchars($paciente['Peso']) ?>">
      </div>

      <div class="form-group">
        <label for="tipo_sangre">Tipo de sangre</label>
        <input type="text" id="tipo_sangre" name="tipo_sangre" class="form-control"
               value="<?= htmlspecialchars($paciente['Tipo_Sangre']) ?>">
      </div>

      <div class="form-group">
        <label for="alergias">Alergias</label>
        <textarea id="alergias" name="alergias" class="form-control"><?= htmlspecialchars($paciente['Alergia']) ?></textarea>
      </div>

      <div class="form-group">
        <label for="padecimientos">Padecimientos</label>
        <textarea id="padecimientos" name="padecimientos" class="form-control"><?= htmlspecialchars($paciente['Padecimientos']) ?></textarea>
      </div>

      <button type="submit" class="btn btn-primary">Actualizar Datos</button>
      <a href="inicioPaciente.php" class="link-btn">← Volver al menú</a>
    </form>
  </div>
</body>
</html>


