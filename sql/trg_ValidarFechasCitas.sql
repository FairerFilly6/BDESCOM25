
CREATE TRIGGER trg_ValidarFechasCitas
ON Cita
AFTER INSERT
AS
BEGIN
    SET NOCOUNT ON;

    -- Variables para recorrer la tabla insertada
    DECLARE @FechaCita DATE,
            @FechaReservacion DATE,
            @ID_Horario INT,
			@Inicio_Horario TIME,
			@Fin_Horario TIME,
            @ID_Medico INT;


    SELECT 
        @FechaCita = Fecha_Cita,
        @FechaReservacion = Fecha_Reservacion,
        @ID_Horario = ID_Horario,
        @ID_Medico = ID_Medico
    FROM inserted;



    -- 1. No permitir citas en el pasado
    IF @FechaCita < CONVERT(DATE,getDate())
    BEGIN
        RAISERROR('No se pueden agendar citas con fecha pasada.', 16, 1);
        ROLLBACK;
        RETURN;
    END

    -- 2. Validar mínimo 48 horas de anticipacion o 3 meses de antelacion
    IF DATEDIFF(HOUR, @FechaReservacion, @FechaCita) < 48
    BEGIN
        RAISERROR('La cita debe reservarse con al menos 48 horas de anticipacion.', 16, 1);
        ROLLBACK;
        RETURN;
    END

	IF DATEDIFF(MONTH, @FechaReservacion, @FechaCita) >  3
    BEGIN
        RAISERROR('La cita debe reservarse con maximo 3 meses e antelacion.', 16, 1);
        ROLLBACK;
        RETURN;
    END


    -- 3. Validar que el horario de la cita esté dentro del horario del médico
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
        RAISERROR('El horario seleccionado no corresponde al horario del medico.', 16, 1);
        ROLLBACK;
        RETURN;
    END
END;
GO



