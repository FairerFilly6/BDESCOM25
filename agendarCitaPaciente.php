

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
    <h3>Agendar cita</h3>

    <form class="formulario-cita" method="POST" action="procesar_cita.php">
    

        <label for="especialidad">Especialidad:</label>
        <select name="especialidad" id="especialidad" required>
            <option  selected disabled value="">-- Selecciona una especialidad --</option>
            <option value="">Nefrologo</option>
            <option value="">Cardiologo</option>
        </select>

        <label for="medico">Médico:</label>
        <select name="medico" id="medico" required>
            <option selected disabled value="">-- Selecciona un médico --</option>
            <option value="">Juan Perez</option>
            <option value="">Miguel Lopez</option>
        </select>

        <label for="fecha">Fecha:</label>
        <select name="fecha" id="fecha" required>
            <option selected disabled value="">-- Selecciona una fecha --</option>
            <option value="">14/1/2025 15:30</option>
            <option value="">10/12/2025 10:30</option>
        </select>

        <button type="submit" class="boton-confirmar">Confirmar</button>
    </form>



    <div class="logout centrar">
        <a class="border" href="#">Cerrar sesión</a>
    </div>


   
</div>

</body>
</html>