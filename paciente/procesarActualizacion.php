<?php
require_once __DIR__ . '/../Clases/Conexion.php';
require_once __DIR__ . '/../Clases/Paciente.php';

$paciente = new Paciente(new Conexion());
$userData = [
    'nombre' => $_POST['nombre'] ?? '',
    'email'  => $_POST['email']  ?? '',
    'pass'   => $_POST['pass']   ?? ''
];
$patData = [
    'estatura'      => $_POST['estatura']      ?? 0,
    'peso'          => $_POST['peso']          ?? 0,
    'sangre'        => $_POST['tipo_sangre']   ?? '',
    'alergias'      => $_POST['alergias']      ?? '',
    'padecimientos' => $_POST['padecimientos'] ?? ''
];

if ($paciente->actualizarPerfil($userData, $patData)) {
    header('Location: inicioPaciente.php?msg=actualizado');
} else {
    header('Location: ActualizarDatosPaciente.php?error=fail');
}
exit;
