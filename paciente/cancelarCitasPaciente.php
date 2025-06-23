<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../index.php");
    exit();
}

include_once __DIR__ . "/../Clases/Conexion.php";
$conn = new Conexion();

// 1) Obtener el ID_Paciente de la sesión
$sql = "
    SELECT P.ID_Paciente AS idPac
      FROM Paciente P
      JOIN Usuario U ON P.CURP = U.CURP
     WHERE U.Email = ?
";
$stmt = $conn->seleccionar($sql, [ $_SESSION['email'] ]);

$citas = [];
if ($stmt) {
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        // 2) Obtener las citas que aún no están canceladas por el paciente
        $consulta = "
            SELECT
              C.Folio_Cita      AS Folio,
              U.Nombre + ' ' + U.Apellido_P AS Medico,
              Esp.Nombre        AS Especialidad,
              CONVERT(VARCHAR(10), C.Fecha_Cita, 23) AS FechaCita,
              CONVERT(VARCHAR(5), H.Inicio_Horario, 108) AS Horario
            FROM Cita C
            JOIN Medico M        ON C.ID_Medico      = M.ID_Medico
            JOIN Empleado E      ON M.ID_Empleado    = E.ID_Empleado
            JOIN Usuario U       ON E.CURP           = U.CURP
            JOIN Especialidad Esp ON M.ID_Especialidad = Esp.ID_Especialidad
            JOIN Horario H       ON C.ID_Horario     = H.ID_Horario
           WHERE C.ID_Paciente   = ?
             AND C.ID_EstatusCita in (1,2)
        ";
        $citas = $conn->seleccionar($consulta, [ $row['idPac'] ])
                     ->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Cancelar Citas</title>
  <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
  <div class="header">
    <h1>Clínica de Especialidad</h1>
  </div>
  <div class="menu centrar">
    <h2>Bienvenido Paciente</h2>
    <h3>Cancelar cita</h3>

    <?php if (empty($citas)): ?>
      <p>No tienes citas activas para cancelar.</p>
    <?php else: ?>
      <table class="tabla-consultas">
        <thead>
          <tr>
            <th>Folio</th>
            <th>Médico</th>
            <th>Especialidad</th>
            <th>Fecha cita</th>
            <th>Horario</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($citas as $c): ?>
            <tr>
              <td><?= htmlspecialchars($c['Folio']) ?></td>
              <td><?= htmlspecialchars($c['Medico']) ?></td>
              <td><?= htmlspecialchars($c['Especialidad']) ?></td>
              <td><?= htmlspecialchars($c['FechaCita']) ?></td>
              <td><?= htmlspecialchars($c['Horario']) ?></td>
              <td>
                <form action="procesarCancelacion.php" method="post">
                  <input type="hidden" name="idCita" value="<?= $c['Folio'] ?>">
                  <button type="submit" class="btn btn-danger">Cancelar</button>
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
