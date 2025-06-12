
create function obtenerCitasPendientes ( @fechaBuscada date ) returns table
as return 
(
	select * from CitasMedico where @fechaBuscada=Fecha_Cita
)

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

create function obtenerCitasPorEspecualidad () returns table
as return (
	select es.Nombre, count(es.Nombre) as CitasPorEspecialidad
	from Cita cit left join Medico med on cit.ID_Medico=med.ID_Medico
	left join Especialidad es on med.ID_Especialidad = es.ID_Especialidad
	group by es.Nombre
)

