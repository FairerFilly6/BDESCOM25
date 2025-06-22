
create view CitasClinica as
	select
		med.ID_Medico, us.Nombre+' '+us.Apellido_P+' '+us.Apellido_M as Medico, us.Email as EmailMedico,
		esp.Nombre as Especialidad,
		pac.ID_Paciente,
		usP.Nombre+' '+usP.Apellido_P+' '+usP.Apellido_M as Paciente,
		usP.Email as EmailPaciente,
		cit.Folio_Cita,
		cit.Fecha_Cita,
		hor.Inicio_Horario,
		hor.Fin_Horario
	from
		Medico med left join Empleado emp on med.ID_Empleado = emp.ID_Empleado
		left join Usuario us on emp.CURP = us.CURP
		left join Especialidad esp on med.ID_Especialidad = esp.ID_Especialidad
		left join Cita cit on med.ID_Medico = cit.ID_Medico 
		left join Horario hor on cit.ID_Horario = hor.ID_Horario
		left join Paciente pac on cit.ID_Paciente=pac.ID_Paciente
		left join Usuario usP on usP.CURP=pac.CURP
			where cit.Folio_Cita is not null

create view HistorialCitasPaciente as
	SELECT Cit.Fecha_Cita, Cit.ID_Paciente, Cit.Folio_Cita  AS Folio, 
                    CONCAT(Us.Nombre, ' ', Us.Apellido_P) AS Medico,
                    Esp.Nombre AS Especialidad,
                    Cit.Fecha_Cita AS FechaCita, 
                    CONCAT(
                        LEFT(Ho.Inicio_Horario,5), ' - ',
                        LEFT(Ho.Fin_Horario,5) )AS Horario,
                    Cit.Fecha_Reservacion AS FechaRes, 
                    Con.Numero AS NumConsultorio,
                    Con.Piso AS PisoConsultorio,
                    EC.EstatusCita AS Estatus
                FROM Cita Cit
                LEFT JOIN Medico Med ON Cit.ID_Medico = Med.ID_Medico
                LEFT JOIN Empleado Emp ON Med.ID_Empleado = Emp.ID_Empleado
                LEFT JOIN Usuario Us ON Emp.CURP = Us.CURP
                LEFT JOIN Horario Ho ON Cit.ID_Horario = Ho.ID_Horario
                LEFT JOIN Especialidad Esp ON Med.ID_Especialidad = Esp.ID_Especialidad
                LEFT JOIN Consultorio Con ON Cit.ID_Consultorio = Con.ID_Consultorio
				LEFT JOIN EstatusCita EC ON Cit.ID_EstatusCita = EC.ID_EstatusCita

CREATE VIEW Especialistas AS
	SELECT M.ID_Medico AS ID, CONCAT(U.nombre,' ',U.Apellido_P) AS Nombre, E.Nombre AS Especialidad, E.Costo_Consulta AS Costo
	FROM Medico M LEFT JOIN Especialidad E on M.ID_Especialidad = E.ID_Especialidad
		LEFT JOIN Empleado Emp ON M.ID_Empleado = Emp.ID_Empleado
		LEFT JOIN Usuario U ON Emp.CURP = U.CURP

CREATE VIEW FacturacionPorPaciente AS
	SELECT 
		P.ID_Paciente,
		U.Nombre + ' ' + U.Apellido_P + ' ' + U.Apellido_M AS NombreCompleto,
		F.ID_Factura,
		F.Fecha AS FechaFactura,
		F.Concepto,
		F.Estatus AS EstatusFactura,
		PAGO.Metodo_Pago,
		PAGO.Total,
		PAGO.Fecha_Pago
	FROM 
		Paciente P JOIN Usuario U ON P.CURP = U.CURP
		JOIN Cita C ON P.ID_Paciente = C.ID_Paciente
		JOIN Factura F ON C.ID_Factura = F.ID_Factura
		LEFT JOIN Pago PAGO ON F.ID_Factura = PAGO.ID_Factura;

CREATE VIEW IntervalosDisponiblesCitas
AS
SELECT ID_Horario AS ID, CONCAT( LEFT(Inicio_Horario,5) , ' - ',  LEFT(Fin_Horario,5) ) AS Horario FROM Horario WHERE ID_Horario <= 13

create or alter view VistaMedico as
select ID_Medico, Cedula_Pro, ID_Empleado, Nombre, Costo_Consulta
from Medico M
full outer join Especialidad E
on M.ID_Especialidad = E.ID_Especialidad
where M.ID_Especialidad is null
or E.ID_Especialidad is null;
