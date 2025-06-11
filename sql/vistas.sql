
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

create function  obtenerEdadPaciente ( @fech_nac date ) returns int
as 
begin

	return datediff(mm, @fech_nac, getdate())/12

end

-- Example: select dbo.obtenerEdadPaciente( cast( '2000-07-11' as date ) ) as Edad

create view HorarioMedicosDisponible as
	select
		med.ID_Medico,
		hor.Inicio_Horario,
		hor.Fin_Horario
	from
	Medico med left join Cita cit on med.ID_Medico=cit.ID_Medico
	left join Horario hor on cit.ID_Horario=hor.ID_Horario
	where cit.Folio_Cita is not null

	select * from Horario hor left join CitasMedico cm on hor.Inicio_Horario = cm.Inicio_Horario

--vista Horarios

CREATE VIEW HorariosDia
AS
SELECT ID_Horario AS ID, CONCAT( LEFT(Inicio_Horario,5) , ' - ',  LEFT(Fin_Horario,5) ) AS Horario FROM Horario WHERE ID_Horario <= 13


--vista especialistas

CREATE VIEW Especialistas
AS
SELECT M.ID_Medico AS ID, CONCAT(U.nombre,' ',U.Apellido_P) AS Nombre, E.Nombre AS Especialidad, E.Costo_Consulta AS Costo FROM Medico M 
LEFT JOIN Especialidad E on M.ID_Especialidad = E.ID_Especialidad
LEFT JOIN Empleado Emp ON M.ID_Empleado = Emp.ID_Empleado
LEFT JOIN Usuario U ON Emp.CURP = U.CURP