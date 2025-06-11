CREATE TRIGGER trg_ValidarFechasCitas
ON Cita
AFTER INSERT
AS
BEGIN
    SET NOCOUNT ON;

    DECLARE @FechaCita DATE,
            @FechaReservacion DATE,
            @ID_Horario INT,
            @ID_Medico INT;

    SELECT 
        @FechaCita = Fecha_Cita,
        @FechaReservacion = Fecha_Reservacion,
        @ID_Horario = ID_Horario,
        @ID_Medico = ID_Medico
    FROM inserted;

    -- 1. No permitir citas en el pasado
    IF @FechaCita < CONVERT(DATE, GETDATE())
    BEGIN
        RAISERROR('No se pueden agendar citas con fecha pasada.', 16, 1);
        ROLLBACK;
        RETURN;
    END

    -- 2. Validar mínimo 48 horas de anticipación
    IF DATEDIFF(HOUR, @FechaReservacion, @FechaCita) < 48
    BEGIN
        RAISERROR('La cita debe reservarse con al menos 48 horas de anticipación.', 16, 1);
        ROLLBACK;
        RETURN;
    END

    -- 3. Validar máximo 3 meses de anticipación
    IF DATEDIFF(MONTH, @FechaReservacion, @FechaCita) > 3
    BEGIN
        RAISERROR('La cita debe reservarse con máximo 3 meses de anticipación.', 16, 1);
        ROLLBACK;
        RETURN;
    END

    -- 4. Validar que el horario de la cita esté dentro del horario del médico
    IF NOT EXISTS (
        SELECT 1
        FROM Medico M
        JOIN Empleado E ON M.ID_Empleado = E.ID_Empleado
        JOIN Horario HMedico ON E.ID_Horario = HMedico.ID_Horario
        JOIN Horario HCita ON HCita.ID_Horario = @ID_Horario
        WHERE M.ID_Medico = @ID_Medico
          AND HCita.Inicio_Horario >= HMedico.Inicio_Horario
          AND HCita.Fin_Horario <= HMedico.Fin_Horario
    )
    BEGIN
        RAISERROR('El horario seleccionado no corresponde al horario del médico.', 16, 1);
        ROLLBACK;
        RETURN;
    END

	--Validar que no tenga citas pendientes con el 

	IF EXISTS (
		SELECT 1
		FROM inserted i
		JOIN Cita c ON c.ID_Paciente = i.ID_Paciente
				   AND c.ID_Medico = i.ID_Medico
				   AND c.ID_EstatusCita IN (1, 2)
				   AND c.Folio_Cita <> i.Folio_Cita 
	)BEGIN
       RAISERROR('El paciente ya tiene una cita pendiente con este médico.', 16, 1);
       ROLLBACK;
       RETURN;
    END


END;
GO