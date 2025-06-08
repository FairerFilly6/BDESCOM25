

CREATE TRIGGER trg_ValidarCitaPendiente
ON Cita
INSTEAD OF INSERT
AS
BEGIN
    SET NOCOUNT ON;

    -- Verifica si hay conflictos
    IF EXISTS (
        SELECT 1
        FROM Cita c
        INNER JOIN inserted i ON c.ID_Paciente = i.ID_Paciente AND c.ID_Medico = i.ID_Medico
        WHERE c.ID_EstatusCita IN (1, 2)
    )
    BEGIN
        RAISERROR('El paciente ya tiene una cita pendiente con este médico.', 16, 1);
        RETURN;
    END

    -- Si no hay conflicto, procede con la inserción
    INSERT INTO Cita (
        ID_Paciente,
        ID_Horario,
        ID_Medico,
        ID_Especialidad,
        Fecha_Cita,
        Fecha_Reservacion,
        ID_Factura,
        ID_Consultorio,
        ID_EstatusCita
    )
    SELECT
        ID_Paciente,
        ID_Horario,
        ID_Medico,
        ID_Especialidad,
        Fecha_Cita,
        Fecha_Reservacion,
        ID_Factura,
        ID_Consultorio,
        ID_EstatusCita
    FROM inserted;
END;
