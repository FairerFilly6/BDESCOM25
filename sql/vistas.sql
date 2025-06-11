
create view CitasMedico as
	select med.ID_Medico,us.Nombre+' '+us.Apellido_P+' '+us.Apellido_M as Medico, us.CURP, Email,
		esp.Nombre as Especialidad,
		cit.Folio_Cita,
		cit.Fecha_Cita,
		CONCAT( LEFT(hor.Inicio_Horario ,5) , ' - ',  LEFT(hor.Fin_Horario,5) ) AS Horario
	from
		Medico med left join Empleado emp on med.ID_Empleado = emp.ID_Empleado
		left join Usuario us on emp.CURP = us.CURP
		left join Especialidad esp on med.ID_Especialidad = esp.ID_Especialidad
		left join Cita cit on med.ID_Medico = cit.ID_Medico 
		left join Horario hor on cit.ID_Horario = hor.ID_Horario
			where cit.Folio_Cita is not null

create view CitasPaciente as
	select pac.ID_Paciente, us.Nombre+' '+us.Apellido_P+' '+us.Apellido_M as Paciente, us.CURP, Email,
		cit.Folio_Cita,
		cit.Fecha_Cita,
		CONCAT( LEFT(hor.Inicio_Horario ,5) , ' - ',  LEFT(hor.Fin_Horario,5) ) AS Horario
	from
		Paciente pac left join Usuario us on pac.CURP = us.CURP
		left join Cita cit on pac.ID_Paciente = cit.ID_Paciente 
		left join Horario hor on cit.ID_Horario = hor.ID_Horario
			where cit.Folio_Cita is not null

create view DetallesCita as
	select cp.Folio_Cita, Medico, Especialidad as EspecialidadDoctor, Paciente, cp.Fecha_Cita, cp.horario as Horario
		from CitasMedico cm inner join CitasPaciente cp on cm.Folio_Cita = cp.Folio_Cita

CREATE VIEW HorariosDia
AS
SELECT ID_Horario AS ID, CONCAT( LEFT(Inicio_Horario,5) , ' - ',  LEFT(Fin_Horario,5) ) AS Horario FROM Horario WHERE ID_Horario <= 13


CREATE VIEW Especialistas
AS
SELECT M.ID_Medico AS ID, CONCAT(U.nombre,' ',U.Apellido_P) AS Nombre, E.Nombre AS Especialidad, E.Costo_Consulta AS Costo FROM Medico M 
LEFT JOIN Especialidad E on M.ID_Especialidad = E.ID_Especialidad
LEFT JOIN Empleado Emp ON M.ID_Empleado = Emp.ID_Empleado
LEFT JOIN Usuario U ON Emp.CURP = U.CURP

create view CorreosUsuarios as
	select us.Nombre+' '+us.Apellido_P+' '+us.Apellido_M as Usuario, us.Email, tu.TipoUsuario
	from Usuario us left join TipoUsuario tu on us.ID_TipoUsuario = tu.ID_TipoUsuario
