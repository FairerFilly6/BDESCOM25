

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>ClinicaDeEspecialidad</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<div class="header">
    <h1>Clínica de especialidad</h1>
</div>

<div class="menu centrar">
    <h2>Bienvenido Paciente</h2>
    <h3>Cancelar citas</h3>
    <table class="tabla-consultas">
        <thead>
            <tr>
                <th>Folio</th>
                <th>Médico</th>
                <th>Especialidad</th>
                <th>Fecha cita</th>
                <th>Fecha reservacion</th>
                <th>Cancelar</th>
            </tr>
        </thead>
        <tbody>
            <!-- Aquí se llenarán los datos con PHP -->
            <tr>
                <td>1</td>
                <td>Juan Hernandez</td>
                <td>Nefrologo</td>
                <td>12/12/2024 12:00</td>
                <td>12/1/2025 17:30</td>
                <td>
                    <a  class="cancelacion"href="#">Cancelar</a>
                </td>
            </tr>
            <tr>
                <td>2</td>
                <td>Miguel Lopez</td>
                <td>Cardiologo</td>
                <td>1/12/2024 12:00</td>
                <td>1/1/2025 10:30</td>
                <td >
                    <a class="cancelacion" href="#">Cancelar</a>
                </td>
            </tr>
        </tbody>
    </table>

    </div>
    <div class="logout centrar">
        <a class="border" href="#">Cerrar sesión</a>
    </div>


   
</div>

</body>
</html>