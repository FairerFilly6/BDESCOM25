<?php
session_start();
if (!isset($_SESSION['email'])) header('Location: ../index.php');
require_once '../Clases/Conexion.php';
$conn = new Conexion();

// Traer citas pendientes de pago
$stmt = $conn->seleccionar("
    SELECT Folio_Cita, Fecha_Cita, Horario, Medico, Especialidad, Total
      FROM HistorialCitasPaciente
     WHERE ID_Paciente = (
         SELECT ID_Paciente FROM Paciente
          WHERE CURP = (SELECT CURP FROM Usuario WHERE Email = :email)
     )
       AND ID_EstatusCita = (
         SELECT ID_EstatusCita FROM EstatusCita WHERE EstatusCita='Agendada pendiente de pago'
       )
", [':email'=>$_SESSION['email']]);

$citas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Pagar Cita</title></head>
<body>
  <h2>Pagar cita</h2>
  <table border="1">
    <tr><th>Folio</th><th>Fecha</th><th>Total</th><th>Acción</th></tr>
    <?php foreach($citas as $c): ?>
      <tr>
        <td><?= $c['Folio_Cita'] ?></td>
        <td><?= $c['Fecha_Cita'] ?></td>
        <td>$<?= number_format($c['Total'],2) ?></td>
        <td>
          <form action="procesarPago.php" method="post">
            <input type="hidden" name="idCita" value="<?= $c['Folio_Cita'] ?>">
            <input type="number" name="monto" step="0.01" value="<?= $c['Total'] ?>" required>
            <button type="submit">Pagar</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
  <p><a href="inicioPaciente.php">← Volver al menú</a></p>
</body>
</html>
