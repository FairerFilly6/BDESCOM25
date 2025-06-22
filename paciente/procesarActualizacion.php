<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../index.php");
    exit();
}

require_once __DIR__ . '/../Clases/Conexion.php';
require_once __DIR__ . '/../Clases/Paciente.php';

$db       = new Conexion();
$paciente = new Paciente($db);

// Recolecta datos del formulario
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

// Intenta actualizar
if ($paciente->actualizarPerfil($userData, $patData)) {
    header('Location: inicioPaciente.php?msg=actualizado');
    exit();
} else {
    // Si falla, vuelve a la vista con error
    header('Location: ActualizarDatosPaciente.php?error=fail');
    exit();
}
