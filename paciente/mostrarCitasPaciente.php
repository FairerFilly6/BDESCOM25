<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../index.php");
    exit();
}

include_once __DIR__ . "/../Clases/Conexion.php";
$conn = new Conexion();

// 1) Obtener el ID_Paciente a partir del email de sesión
$sql = "
    SELECT P.ID_Paciente AS id
      FROM Paciente P
      JOIN Usuario U ON P.CURP = U.CURP
     WHERE U.Email = ?
";
$params = [ $_SESSION['email'] ];
$stmt   = $conn->seleccionar($sql, $params);

$resConsulta = [];
if ($stmt) {
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        // 2) Consultar las citas directamente de la tabla Cita
        $consulta = "
            SELECT
              C.Folio_Cita             AS Folio,
              U.Nombre + ' ' + U.Apellido_P AS Medico,
              Esp.Nombre               AS Especialidad,
              CONVERT(VARCHAR(10), C.Fecha_Cita, 23)         AS FechaCita,
              CONVERT(VARCHAR(5), H.Inicio_Horario, 108)     AS Horario,
              CONVERT(VARCHAR(10), C.Fecha_Reservacion, 23)  AS FechaRes,
              Con.Numero               AS NumConsultorio,
              Con.Piso                 AS PisoConsultorio,
              EC.EstatusCita           AS Estatus,
              format(C.Monto_Devuelto,'N2') as Devolucion
            FROM Cita C
            JOIN Medico M     ON C.ID_Medico      = M.ID_Medico
            JOIN Empleado E   ON M.ID_Empleado    = E.ID_Empleado
            JOIN Usuario U    ON E.CURP           = U.CURP
            JOIN Especialidad Esp ON M.ID_Especialidad = Esp.ID_Especialidad
            JOIN Horario H    ON C.ID_Horario     = H.ID_Horario
            JOIN Consultorio Con ON C.ID_Consultorio = Con.ID_Consultorio
            JOIN EstatusCita EC  ON C.ID_EstatusCita   = EC.ID_EstatusCita
           WHERE C.ID_Paciente = ?
        ";
        $paramConsulta = [ $row['id'] ];
        $resConsulta   = $conn->seleccionar($consulta, $paramConsulta)->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mostrar Citas Paciente</title>
  <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
  <div class="header">
    <h1>Clínica de Especialidad</h1>
  </div>
  <div class="menu centrar">
    <h2>Bienvenido Paciente</h2>
    <h3>Citas registradas</h3>
    <table class="tabla-consultas">
      <thead>
        <tr>
          <th>Folio</th>
          <th>Médico</th>
          <th>Especialidad</th>
          <th>Fecha cita</th>
          <th>Horario</th>
          <th>Fecha reservación</th>
          <th>Consultorio</th>
          <th>Piso</th>
          <th>Estatus</th>
          <th>Devolucion</th>
        </tr>
      </thead>
      <tbody>
      <?php if (!empty($resConsulta)): ?>
        <?php foreach ($resConsulta as $cita): ?>
          <tr>
            <td><?= htmlspecialchars($cita['Folio']) ?></td>
            <td><?= htmlspecialchars($cita['Medico']) ?></td>
            <td><?= htmlspecialchars($cita['Especialidad']) ?></td>
            <td><?= htmlspecialchars($cita['FechaCita']) ?></td>
            <td><?= htmlspecialchars($cita['Horario']) ?></td>
            <td><?= htmlspecialchars($cita['FechaRes']) ?></td>
            <td><?= htmlspecialchars($cita['NumConsultorio']) ?></td>
            <td><?= htmlspecialchars($cita['PisoConsultorio']) ?></td>
            <td><?= htmlspecialchars($cita['Estatus']) ?></td>
            <td><?= htmlspecialchars($cita['Devolucion']) ?></td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="9">No tienes citas registradas.</td>
        </tr>
      <?php endif; ?>
      </tbody>
    </table>
    <p><a class="border" href="inicioPaciente.php">← Volver al menú</a></p>
    <p><a class="border" href="logout.php">Cerrar sesión</a></p>
  </div>
</body>
</html>
