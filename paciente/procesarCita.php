<?php
require_once __DIR__ . '/../Clases/Conexion.php';
require_once __DIR__ . '/../Clases/Paciente.php';

$db       = new Conexion();
$paciente = new Paciente($db);

try {
    if ($paciente->agendarCita(
        (int)$_POST['horario'],
        (int)$_POST['especialidad'],
        $_POST['fecha']
    )) {
        header('Location: mostrarCitasPaciente.php?msg=agendada');
        exit;
    }
} catch (Exception $e) {
    header('Location: agendarCitaPaciente.php?error=no_consultorio');
    exit;
}
