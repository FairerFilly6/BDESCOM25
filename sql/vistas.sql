
-- Sirve para relacionar a los medicos con las fechas de citas pendientes
create view CitasMedico as
	select us.Nombre+' '+us.Apellido_P+' '+us.Apellido_M as Medico, us.CURP, Email,
		esp.Nombre as Especialidad,
		cit.Fecha_Cita,
		hor.Inicio_Horario, hor.Fin_Horario
	from
		Medico med left join Empleado emp on med.ID_Empleado = emp.ID_Empleado
		left join Usuario us on emp.CURP = us.CURP
		left join Especialidad esp on med.ID_Especialidad = esp.ID_Especialidad
		left join Cita cit on med.ID_Medico = cit.ID_Medico 
		left join Horario hor on cit.ID_Horario = hor.ID_Horario
			where cit.Folio_Cita is not null
-- Sirve para buscar a los medicos y sus citas en un dia especifico
create function obtenerCitasPendientes ( @fechaBuscada date ) returns table
as return 
(
	select * from CitasMedico where @fechaBuscada=Fecha_Cita
)
-- Example: select * from obtenerCitasPendientes(cast( '2024-06-28' as date ))