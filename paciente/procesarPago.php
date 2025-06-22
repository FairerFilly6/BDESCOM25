<?php
require_once __DIR__ . '/../Clases/Conexion.php';
require_once __DIR__ . '/../Clases/Paciente.php';

$paciente = new Paciente(new Conexion());
$idCita   = $_POST['idCita'] ?? null;
$monto    = $_POST['monto']  ?? null;

if ($idCita && $monto !== null && $paciente->pagarCita((int)$idCita, (float)$monto)) {
    header('Location: mostrarCitasPaciente.php?msg=pagada');
} else {
    header('Location: pagoCitasPaciente.php?error=fail');
}
exit;
