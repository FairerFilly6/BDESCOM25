<?php
require_once __DIR__ . '/Conexion.php';

class Paciente {
    private $db;
    private $idPaciente;

    public function __construct(Conexion $conn) {
        session_start();
        if (empty($_SESSION['email'])) {
            header('Location: ../index.php');
            exit;
        }
        $this->db = $conn;
        // Obtener ID_Paciente a partir del email de sesión
        $sql = "
            SELECT P.ID_Paciente
              FROM Paciente P
              JOIN Usuario U ON P.CURP = U.CURP
             WHERE U.Email = :email
        ";
        $stmt = $this->db->seleccionar($sql, [':email' => $_SESSION['email']]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->idPaciente = $row['ID_Paciente'] ?? null;
    }

    private function getConsultorioDisponible(int $idHorario, string $fecha): ?int {
        $sql = "
            SELECT TOP 1 ID_Consultorio
              FROM Consultorio
             WHERE ID_Consultorio NOT IN (
                   SELECT ID_Consultorio
                     FROM Cita
                    WHERE Fecha_Cita = :fecha
                      AND ID_Horario = :idHor
                      AND ID_EstatusCita NOT IN (
                          SELECT ID_EstatusCita 
                            FROM EstatusCita 
                           WHERE EstatusCita = 'Cancelada Paciente'
                      )
               )
        ";
        $stmt = $this->db->seleccionar($sql, [
            ':fecha' => $fecha,
            ':idHor' => $idHorario
        ]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['ID_Consultorio'] ?? null;
    }

    public function agendarCita(int $idHorario, int $idMedico, string $fechaCita): bool {
        $idCons = $this->getConsultorioDisponible($idHorario, $fechaCita);
        if (!$idCons) {
            throw new Exception("No hay consultorios disponibles en esa fecha y horario.");
        }

        $conn = $this->db->getConexion();
        try {
            $conn->beginTransaction();

            // Factura
            $sqlF = "
                INSERT INTO Factura(Fecha, Concepto, Estatus)
                VALUES (GETDATE(), 'Consulta médica', 'Agendada pendiente de pago')
            ";
            $this->db->insertar($sqlF, []);
            $idFactura = $this->db->lastInsertId();

            // Cita
            $sqlC = "
                INSERT INTO Cita
                  (ID_Paciente, ID_Horario, ID_Medico,
                   Fecha_Cita, Fecha_Reservacion,
                   ID_Factura, ID_Consultorio, ID_EstatusCita)
                VALUES
                  (:idPac, :idHor, :idMed,
                   :fechaCita, GETDATE(),
                   :idFact, :idCons,
                   (SELECT ID_EstatusCita 
                      FROM EstatusCita 
                     WHERE EstatusCita = 'Agendada pendiente de pago'))
            ";
            $this->db->insertar($sqlC, [
                ':idPac'     => $this->idPaciente,
                ':idHor'     => $idHorario,
                ':idMed'     => $idMedico,
                ':fechaCita' => $fechaCita,
                ':idFact'    => $idFactura,
                ':idCons'    => $idCons
            ]);

            $conn->commit();
            return true;
        } catch (Exception $e) {
            $conn->rollBack();
            throw $e;
        }
    }

    public function cancelarCita(int $idCita): bool {
        $sql = "
            UPDATE Cita
               SET ID_EstatusCita = (
                   SELECT ID_EstatusCita 
                     FROM EstatusCita 
                    WHERE EstatusCita = 'Cancelada Paciente'
               )
             WHERE Folio_Cita = :idCita
               AND ID_Paciente = :idPac
        ";
        $sqlProcedure ='exec SP_CANCELACION_CITA ?,?';
        $paramAlta =array($idCita, 1);

        $exitoUsuario = $this->db->insertar($sqlProcedure,$paramAlta);

        return $this->db->modificar($sql, [
            ':idCita' => $idCita,
            ':idPac'  => $this->idPaciente
        ]);
    }

    public function pagarCita(int $idCita, float $monto): bool {
        $conn = $this->db->getConexion();
        try {
            $conn->beginTransaction();

            // Pago
            $sql1 = "
                INSERT INTO Pago(ID_Factura, Metodo_Pago, Fecha_Pago, Total, Estatus_Pago)
                SELECT C.ID_Factura, 'WEB', GETDATE(), :monto, 'Exito'
                  FROM Cita C
                 WHERE C.Folio_Cita = :idCita
            ";
            $this->db->insertar($sql1, [
                ':monto'  => $monto,
                ':idCita' => $idCita
            ]);

            // Factura → Pagada
            $sql2 = "
                UPDATE Factura
                   SET Estatus = 'Pagada'
                 WHERE ID_Factura = (
                     SELECT ID_Factura FROM Cita WHERE Folio_Cita = :idCita
                 )
            ";
            $this->db->modificar($sql2, [':idCita' => $idCita]);

            // Cita → Pagada pendiente por atender
            $sql3 = "
                UPDATE Cita
                   SET ID_EstatusCita = (
                       SELECT ID_EstatusCita 
                         FROM EstatusCita 
                        WHERE EstatusCita = 'Pagada pendiente por atender'
                   )
                 WHERE Folio_Cita = :idCita
                   AND ID_Paciente = :idPac
            ";
            $this->db->modificar($sql3, [
                ':idCita' => $idCita,
                ':idPac'  => $this->idPaciente
            ]);

            $conn->commit();
            return true;
        } catch (Exception $e) {
            $conn->rollBack();
            return false;
        }
    }

    public function obtenerHistorial(): array {
        $sql = "
            SELECT *
              FROM HistorialCitasPaciente
             WHERE ID_Paciente = :idPac
             ORDER BY Fecha_Cita DESC
        ";
        $stmt = $this->db->seleccionar($sql, [':idPac' => $this->idPaciente]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function actualizarPerfil(array $user, array $datos): bool {
        $conn = $this->db->getConexion();
        try {
            $conn->beginTransaction();

            // 1) Actualiza Usuario
            if (!empty($user['pass'])) {
                // Con contraseña nueva
                $sqlU = "
                    UPDATE Usuario
                       SET Nombre = :nombre,
                           Email  = :email,
                           Pwd    = :pass
                     WHERE CURP = (
                         SELECT CURP FROM Paciente WHERE ID_Paciente = :idPac
                     )
                ";
                $paramsU = [
                    ':nombre' => $user['nombre'],
                    ':email'  => $user['email'],
                    ':pass'   => $user['pass'],
                    ':idPac'  => $this->idPaciente
                ];
            } else {
                // Sin tocar contraseña
                $sqlU = "
                    UPDATE Usuario
                       SET Nombre = :nombre,
                           Email  = :email
                     WHERE CURP = (
                         SELECT CURP FROM Paciente WHERE ID_Paciente = :idPac
                     )
                ";
                $paramsU = [
                    ':nombre' => $user['nombre'],
                    ':email'  => $user['email'],
                    ':idPac'  => $this->idPaciente
                ];
            }
            $this->db->modificar($sqlU, $paramsU);

            // 2) Actualiza Paciente
            $sqlP = "
                UPDATE Paciente
                   SET Estatura    = :estatura,
                       Peso        = :peso,
                       Tipo_Sangre = :sangre,
                       Alergia     = :alergias,
                       Padecimientos = :padecimientos
                 WHERE ID_Paciente = :idPac
            ";
            $paramsP = [
                ':estatura'      => $datos['estatura'],
                ':peso'          => $datos['peso'],
                ':sangre'        => $datos['sangre'],
                ':alergias'      => $datos['alergias'],
                ':padecimientos' => $datos['padecimientos'],
                ':idPac'         => $this->idPaciente
            ];
            $this->db->modificar($sqlP, $paramsP);

            $conn->commit();
            return true;
        } catch (Exception $e) {
            $conn->rollBack();
            // Opcional: guarda $e->getMessage() en un log
            return false;
        }
    }
}
