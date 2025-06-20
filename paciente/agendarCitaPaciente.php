<?php
session_start();
if (!isset($_SESSION['email'])) header('Location: ../index.php');
require_once '../Clases/Conexion.php';
$conn = new Conexion();

// Traer especialistas y horarios
$esp = $conn->seleccionar('SELECT ID, Nombre, Especialidad, Costo FROM Especialistas', []);
$hrs = $conn->seleccionar('SELECT ID, Horario FROM IntervalosDisponiblesCitas', []);
?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Agendar Cita</title></head>
<body>
  <h2>Agendar cita</h2>

  <!-- Mensaje de no hay consultorio -->
  <?php if (isset($_GET['error']) && $_GET['error']==='no_consultorio'): ?>
    <div style="color:red;">
      No hay consultorios disponibles en la fecha u horario seleccionado.
    </div>
  <?php endif; ?>

  <form action="procesarCita.php" method="post">
    <label>Especialista:</label>
    <select name="especialidad" required>
      <?php foreach($esp as $e): ?>
        <option value="<?= $e['ID'] ?>">
          <?= htmlspecialchars("{$e['Nombre']} – {$e['Especialidad']} (${$e['Costo']})") ?>
        </option>
      <?php endforeach; ?>
    </select><br>

    <label>Horario:</label>
    <select name="horario" required>
      <?php foreach($hrs as $h): ?>
        <option value="<?= $h['ID'] ?>">
          <?= htmlspecialchars($h['Horario']) ?>
        </option>
      <?php endforeach; ?>
    </select><br>

    <label>Fecha:</label>
    <input type="date" name="fecha" required><br>

    <button type="submit">Agendar</button>
  </form>
  <p><a href="inicioPaciente.php">← Volver al menú</a></p>
</body>
</html>
