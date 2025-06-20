<?php
session_start();
if (!isset($_SESSION['email'])) header('Location: ../index.php');
require_once '../Clases/Conexion.php';
$conn = new Conexion();

// Traer citas activas (no canceladas por paciente)
$stmt = $conn->seleccionar("
    SELECT Folio_Cita, Fecha_Cita, Horario, Medico, Especialidad
      FROM HistorialCitasPaciente
     WHERE ID_Paciente = (
         SELECT ID_Paciente FROM Paciente
          WHERE CURP = (SELECT CURP FROM Usuario WHERE Email = :email)
     )
       AND ID_EstatusCita <> (
         SELECT ID_EstatusCita FROM EstatusCita WHERE EstatusCita='Cancelada Paciente'
       )
", [':email'=>$_SESSION['email']]);

$citas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Cancelar Cita</title></head>
<body>
  <h2>Cancelar cita</h2>
  <table border="1">
    <tr><th>Folio</th><th>Fecha</th><th>Horario</th><th>Médico</th><th>Especialidad</th><th>Acción</th></tr>
    <?php foreach($citas as $c): ?>
      <tr>
        <td><?= $c['Folio_Cita'] ?></td>
        <td><?= $c['Fecha_Cita'] ?></td>
        <td><?= htmlspecialchars($c['Horario']) ?></td>
        <td><?= htmlspecialchars($c['Medico']) ?></td>
        <td><?= htmlspecialchars($c['Especialidad']) ?></td>
        <td>
          <form action="procesarCancelacion.php" method="post">
            <input type="hidden" name="idCita" value="<?= $c['Folio_Cita'] ?>">
            <button type="submit">Cancelar</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
  <p><a href="inicioPaciente.php">← Volver al menú</a></p>
</body>
</html>
