<?php
session_start();
if (!isset($_SESSION['email'])) {
    header('Location: ../index.php');
    exit();
}
require_once __DIR__ . '/../Clases/Conexion.php';
$conn = new Conexion();

// Traer especialidades y horarios
$esp = $conn->seleccionar('SELECT ID_Especialidad AS ID, Nombre, Costo_Consulta FROM Especialidad', []);
$hrs = $conn->seleccionar('SELECT ID_Horario, Inicio_Horario, Fin_Horario FROM Horario', []);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Agendar Cita</title>
  <link rel="stylesheet" href="../css/styles.css">
  <link rel="stylesheet" href="../css/paciente.css">
</head>
<body>
  <div class="header">
    <h1>Clínica de Especialidad</h1>
  </div>

  <div class="menu centrar paciente-container">
    <h2>Agendar Cita</h2>

    <?php if (isset($_GET['error']) && $_GET['error'] === 'no_consultorio'): ?>
      <div class="alert alert-danger">
        No hay consultorios disponibles en la fecha u horario seleccionado.
      </div>
    <?php endif; ?>

    <form class="paciente-form" action="procesarCita.php" method="post">
      <div class="form-group">
        <label for="especialidad">Especialidad</label>
        <select id="especialidad" name="especialidad" class="form-control" required>
          <?php foreach ($esp as $e): ?>
            <option value="<?= $e['ID'] ?>">
              <?= htmlspecialchars("{$e['Nombre']} ($".number_format($e['Costo_Consulta'],2).")") ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group">
        <label for="horario">Horario</label>
        <select id="horario" name="horario" class="form-control" required>
          <?php foreach ($hrs as $h): ?>
            <option value="<?= $h['ID_Horario'] ?>">
              <?= htmlspecialchars(substr($h['Inicio_Horario'],0,5).' – '.substr($h['Fin_Horario'],0,5)) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group">
        <label for="fecha">Fecha</label>
        <input type="date" id="fecha" name="fecha" class="form-control" required>
      </div>

      <button type="submit" class="btn btn-primary">Agendar</button>
      <a href="inicioPaciente.php" class="link-btn">← Volver al menú</a>
    </form>
  </div>
</body>
</html>
