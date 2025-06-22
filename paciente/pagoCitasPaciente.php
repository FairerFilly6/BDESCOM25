<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../index.php");
    exit();
}

include_once __DIR__ . "/../Clases/Conexion.php";
$conn = new Conexion();

// 1) Obtener el ID_Paciente según el email de sesión
$sql = "
    SELECT P.ID_Paciente AS idPac
      FROM Paciente P
      JOIN Usuario U ON P.CURP = U.CURP
     WHERE U.Email = ?
";
$stmt = $conn->seleccionar($sql, [ $_SESSION['email'] ]);

$citasPendientes = [];
if ($stmt) {
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        // 2) Consultar citas con estatus 'Agendada pendiente de pago'
        $consulta = "
            SELECT
              C.Folio_Cita                      AS Folio,
              Esp.Nombre                        AS Especialidad,
              CONVERT(VARCHAR(10), C.Fecha_Cita, 23)        AS FechaCita,
              CONVERT(VARCHAR(5),  H.Inicio_Horario, 108)   AS Horario,
              Esp.Costo_Consulta                AS Total
            FROM Cita C
            JOIN Medico M       ON C.ID_Medico      = M.ID_Medico
            JOIN Empleado E     ON M.ID_Empleado    = E.ID_Empleado
            JOIN Usuario U      ON E.CURP           = U.CURP
            JOIN Especialidad Esp ON M.ID_Especialidad = Esp.ID_Especialidad
            JOIN Horario H      ON C.ID_Horario     = H.ID_Horario
            JOIN EstatusCita EC ON C.ID_EstatusCita = EC.ID_EstatusCita
           WHERE C.ID_Paciente   = ?
             AND EC.EstatusCita   = 'Agendada pendiente de pago'
           ORDER BY C.Fecha_Cita DESC
        ";
        $citasPendientes = $conn
            ->seleccionar($consulta, [ $row['idPac'] ])
            ->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Pagar Citas</title>
  <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
  <div class="header">
    <h1>Clínica de Especialidad</h1>
  </div>
  <div class="menu centrar">
    <h2>Bienvenido Paciente</h2>
    <h3>Citas pendientes de pago</h3>

    <?php if (empty($citasPendientes)): ?>
      <p>No tienes citas pendientes de pago.</p>
    <?php else: ?>
      <table class="tabla-consultas">
        <thead>
          <tr>
            <th>Folio</th>
            <th>Especialidad</th>
            <th>Fecha cita</th>
            <th>Horario</th>
            <th>Total</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($citasPendientes as $c): ?>
            <tr>
              <td><?= htmlspecialchars($c['Folio']) ?></td>
              <td><?= htmlspecialchars($c['Especialidad']) ?></td>
              <td><?= htmlspecialchars($c['FechaCita']) ?></td>
              <td><?= htmlspecialchars($c['Horario']) ?></td>
              <td>$<?= number_format($c['Total'], 2) ?></td>
              <td>
                <form action="procesarPago.php" method="post">
                  <input type="hidden" name="idCita"  value="<?= $c['Folio'] ?>">
                  <input type="hidden" name="monto"   value="<?= $c['Total'] ?>">
                  <button type="submit" class="btn btn-success">Pagar</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>

    <p><a class="border" href="inicioPaciente.php">← Volver al menú</a></p>
    <p><a class="border" href="logout.php">Cerrar sesión</a></p>
  </div>
</body>
</html>
