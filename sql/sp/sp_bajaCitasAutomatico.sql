
select cast( Fecha_Reservacion as datetime ) as FR from Cita

select * from EstatusCita

ALTER PROCEDURE CancelacionFaltaDePago 
AS 
BEGIN
    UPDATE cita SET cita.ID_EstatusCita = 3
    select * FROM 
		Cita cita JOIN Horario horario ON cita.ID_Horario = horario.ID_Horario
    WHERE 
        ID_EstatusCita = 1 and
        DATEDIFF(minute, Fecha_Reservacion, GETDATE()) > 480
END


CREATE PROCEDURE PagoCitas
	@idFactura INT, 
	@metodoPago NVARCHAR
AS 
BEGIN

	DECLARE @Costo INT
	SELECT @Costo = E.Costo_Consulta FROM cita C
	LEFT JOIN Medico M ON C.ID_Medico = M.ID_Medico
	LEFT JOIN Especialidad E ON M.ID_Especialidad = E.ID_Especialidad
	WHERE C.ID_Factura = @idFactura

	INSERT INTO Pago  (ID_Factura,Metodo_Pago,Fecha_Pago,Total,Estatus_Pago) 
	VALUES (@idFactura,@metodoPago,GETDATE(),@Costo,'Exito')

	UPDATE Cita 
		SET ID_EstatusCita = 2
		WHERE ID_Factura = @idFactura
END