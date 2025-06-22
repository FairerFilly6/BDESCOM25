
<?php
    session_start();

    if (!isset($_SESSION['email'])) {
        header("Location: ../index.php");
        exit();
    }

    include_once("../../Clases/Conexion.php");
    $conn = new Conexion();

    $sqlMedico =
        "select
            u.CURP as ID,
            Nombre+' '+Apellido_P+' '+Apellido_M as Nombre
        from
            Medico r left join Empleado e on r.ID_Empleado = e.ID_Empleado
            left join Usuario u on e.CURP = u.CURP where u.CURP is not null and u.Estatus<>'Inactivo'";

    $stmtMedico = $conn->seleccionar($sqlMedico);
        $message=" ";
    if ( !empty($_POST) ) {
        try {
            $medico = $_POST['Medico'];

            $sqlUpdate = " update Usuario set Estatus='Inactivo' where CURP=? ";

            $exitoUsuario = $conn->insertar($sqlUpdate,array($medico));
            if ($exitoUsuario) {
                echo "<script>alert('Se ha dado de baja con éxito');</script>";
            } else {
                echo "<script>alert('No se ha dado de baja con éxito');</script>";
            }
        } catch ( PDOException $e) {
            $parts = explode("]",$e->getMessage());
            $end = end($parts);
            $message = trim($end);
            //echo "<script>alert('".$message."');</script>";
        }
        
    }


    //$resConsulta = $conn->seleccionar($sql);
    
    
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear cuenta</title>
    <link rel="stylesheet" href="../../css/styles.css">
    

</head>
<body>
    <div class="header">
    <h1>Clínica de especialidad</h1>
    </div>
    

    <div class=" centrar">
        <h2>Baja Doctor</h2>
        
    </div>
    <form class="crear-cuenta" action="bajaDoctor.php" method="POST">
            <fieldset>

                <label for="medico">Seleccionar Un Medico</label>
                <select name="Medico" id="medico" required>
                    <option value="" disabled selected >Seleccione un Medico</option>
                        <?php
                            if($stmtMedico){
                                foreach ($stmtMedico as $row) {
                                    echo '<option value ="'.$row['ID'] .'" >';
                                    echo $row['Nombre'] ;
                                    echo '</option>';
                                }     
                            }
                        ?>
                </select>

                
            </fieldset>
            <?php
                if ($message!=" ") {
                    echo '<p class="alerta rojo">'.$message.'</p>';
                }
            ?>
            

            
            <button type="submit" class="boton-confirmar">Dar de Baja</button>
    </form>

        <div class="logout centrar">
            <a class="border" href="../inicioRecepcionista.php">Regresar al menú principal</a>
        </div>

   
    
</body>
</html>