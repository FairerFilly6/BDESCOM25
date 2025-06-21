

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>ClinicaDeEspecialidad</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>

<div class="header">
    <h1>Clínica de especialidad</h1>
</div>

<div class="menu centrar">
    <h2>Bienvenido recepcionista</h2>

    <div class=" border ">
        <h3 class="centrar">Pacientes</h3>
        
        <ul class="lista-opciones">
            <li class="elemento-opciones">
                <a href="paciente/mostrarPacienteR.php">Ver pacientes</a>
            </li>
            <li class="elemento-opciones">
                <a href="paciente/CrearCuentaR.php">Alta de pacientes</a>
            </li>
            <li class="elemento-opciones">
                <a href="paciente/modPacienteR.php">Modificar datos de paciente</a>
            </li>


        </ul>
    </div>

    <div class=" border ">
        <h3 class="centrar">Recepcionistas</h3>
        
        <ul class="lista-opciones">
            <li class="elemento-opciones">
                <a href="recepcionista/verRecepcionista.php">Ver recepcionistas</a>
            </li>
            <li class="elemento-opciones">
                <a href="recepcionista/altaRecepcionista.php">Alta de recepcionistas</a>
            </li>
            <li class="elemento-opciones">
                <a href="recepcionista/bajaRecepcionista.php">Baja de recepcionistas</a>
            </li>

        </ul>
    </div>

    <div class=" border ">
        <h3 class="centrar">Doctores</h3>
        
        <ul class="lista-opciones">
            <li class="elemento-opciones">
                <a href="doctor/doctorRecepcionista.php">Ver doctores</a>
            </li>
            <li class="elemento-opciones">
                <a href="doctor/altaDoctor.php">Alta de doctor</a>
            </li>
            <li class="elemento-opciones">
                <a href="doctor/bajaDoctor.php">Baja de doctor</a>
            </li>

        </ul>
    </div>

    <div class=" border ">
        <h3 class="centrar">Citas</h3>
        
        <ul class="lista-opciones">
            <li class="elemento-opciones">
                <a href="cita/verCita.php">Ver citas</a>
            </li>
            <li class="elemento-opciones">
                <a href="cita/altaCita.php">Alta de cita</a>
            </li>
            <li class="elemento-opciones">
                <a href="cita/bajaCita.php">Cancelar cita</a>
            </li>

        </ul>
    </div>

    <div class=" border ">
        <h3 class="centrar">Ventas</h3>
        
        <ul class="lista-opciones">
            <li class="elemento-opciones">
                <a href="venta/CrearVenta.php">Nueva venta</a>
            </li>
            <li class="elemento-opciones">
                <a href="venta/mostrarVentas.php">Mostrar venta</a>
            </li>
            

        </ul>
    </div>

    <div class=" border ">
        <h3 class="centrar">Medicamentos</h3>
        
        <ul class="lista-opciones">
            <li class="elemento-opciones">
                <a href="medicamento/crearMedicamento.php">Nuevo medicamento</a>
            </li>
            <li class="elemento-opciones">
                <a href="medicamento/mostrarMedicamentos.php">Mostrar medicamento</a>
            </li>
            <li class="elemento-opciones">
                <a href="medicamento/modMedicamento.php">Modificar medicamento</a>
            </li>
            

        </ul>
    </div>

    <div class=" border ">
        <h3 class="centrar">Servicios</h3>
        
        <ul class="lista-opciones">
            <li class="elemento-opciones">
                <a href="servicio/crearServicio.php">Nuevo servicio</a>
            </li>
            <li class="elemento-opciones">
                <a href="servicio/mostrarServicios.php">Mostrar servicios</a>
            </li>
            <li class="elemento-opciones">
                <a href="servicio/modServicio.php">Modificar servicios</a>
            </li>
            

        </ul>
    </div>
    <div class=" border ">
        <h3 class="centrar">Pagos</h3>
        
        <ul class="lista-opciones">
            <li class="elemento-opciones">
                <a href="pago/mostrarPagos.php">Ver pagos</a>
            </li>

        </ul>
    </div>

    <div class="logout centrar">
        <a class="border" href="#">Cerrar sesión</a>
    </div>


   
</div>

</body>
</html>