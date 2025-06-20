<?php
require_once __DIR__ . '/../Clases/Conexion.php';
require_once __DIR__ . '/../Clases/Paciente.php';

$paciente = new Paciente(new Conexion());
$idCita   = $_POST['idCita'] ?? null;

if ($idCita && $paciente->cancelarCita((int)$idCita)) {
    header('Location: mostrarCitasPaciente.php?msg=cancelada');
} else {
    header('Location: cancelarCitasPaciente.php?error=fail');
}
exit;
