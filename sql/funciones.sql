
create function obtenerHorarioServicioMedico( @idMedico int ) returns table
as return (
	select ho.Inicio_Horario, ho.Fin_Horario from Medico med left join Empleado emp on med.ID_Empleado = emp.ID_Empleado
	left join Horario ho on emp.ID_Horario = ho.ID_Horario where med.ID_Medico=@idMedico
)

create function obtenerDisponibilidadMedico( @idMedico int, @fecha date ) returns table
as return (
	select ho.Inicio_Horario, ho.Fin_Horario,
		case
			when cc.Inicio_Horario >= serv.Inicio_Horario and cc.Fin_Horario <=serv.Fin_Horario then 'Ocupado'
			when ho.Inicio_Horario < serv.Inicio_Horario or ho.Fin_Horario > serv.Fin_Horario then 'Fuera de servicio'
			else 'Disponible'
		end as Disponibilidad
		from Horario ho left join CitasClinica cc on ho.Inicio_Horario=cc.Inicio_Horario and ho.Fin_Horario=cc.Fin_Horario and cc.ID_Medico=@idMedico and cc.Fecha_Cita=@fecha
		
		cross apply dbo.obtenerHorarioServicioMedico(@idMedico) serv where ho.ID_Horario <> 14 and ho.ID_Horario <> 15
)

CREATE FUNCTION fn_MedicoDisponible (@ID_Medico INT, @Fecha_Cita DATE, @ID_Horario INT) RETURNS BIT
AS
	BEGIN
		DECLARE @Disponible BIT

		IF EXISTS (
			SELECT 1
			FROM Cita
			WHERE ID_Medico = @ID_Medico
			  AND Fecha_Cita = @Fecha_Cita
			  AND ID_Horario = @ID_Horario
		)
		BEGIN
			SET @Disponible = 0 -- No disponible
		END
		ELSE
		BEGIN
			SET @Disponible = 1 -- Disponible
		END

		RETURN @Disponible
	END

create function obtenerCitasPorEspecialidad () returns table
as return (
	select es.Nombre, count(es.Nombre) as CitasPorEspecialidad
	from Cita cit left join Medico med on cit.ID_Medico=med.ID_Medico
	left join Especialidad es on med.ID_Especialidad = es.ID_Especialidad
	group by es.Nombre
)

CREATE FUNCTION obtenerTotalVenta ( @idVenta INT) RETURNS MONEY AS  
BEGIN
	DECLARE @sumaServicios MONEY 
	DECLARE @sumaMedicamentos MONEY 
	DECLARE @totalVenta MONEY 

	SELECT @sumaServicios =  SUM(DS.Cantidad * S.Costo) FROM DetalleServicio DS 
	LEFT JOIN  Servicio S ON DS.ID_Servicio = S.ID_Servicio
	WHERE DS.ID_Venta = @idVenta

	SELECT @sumaMedicamentos = SUM(DM.Cantidad *M.Precio)  FROM DetalleMedicamento DM
	LEFT JOIN  Medicamento M ON DM.ID_Medicamento = M.ID_Medicamento
	WHERE DM.ID_Venta = @idVenta

	SELECT @totalVenta = @sumaMedicamentos + @sumaServicios

	RETURN @totalVenta
END

CREATE FUNCTION obtenerTotalVentasEspecialidad ( @idEspecialidad INT) RETURNS MONEY AS  
BEGIN
	DECLARE @totalVentasEsp MONEY 

	SELECT @totalVentasEsp  = SUM(E.Costo_Consulta) FROM Cita C
	LEFT JOIN Medico M ON C.ID_Medico = M.ID_Medico
	LEFT JOIN Especialidad E ON M.ID_Especialidad = E.ID_Especialidad
	WHERE E.ID_Especialidad = @idEspecialidad

	RETURN @totalVentasEsp

END


CREATE FUNCTION obtenerTotalVentasMedicamento ( @idMedicamento INT) RETURNS MONEY AS  
BEGIN
	DECLARE @totalVentasMed MONEY 

	SELECT @totalVentasMed = SUM (DM.Cantidad * M.Precio ) FROM DetalleMedicamento DM
	LEFT JOIN Medicamento M ON DM.ID_Medicamento = M.ID_Medicamento
	WHERE M.ID_Medicamento = @idMedicamento

	RETURN @totalVentasMed

END

