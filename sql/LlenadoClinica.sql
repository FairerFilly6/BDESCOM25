USE ClinicaEspecialidadV1



USE ClinicaEspecialidadV1

INSERT INTO TipoUsuario VALUES ('Paciente')

INSERT INTO TipoUsuario VALUES ('Empleado')

INSERT INTO Usuario 
VALUES ('CURPPRUEBAA112233','Juan', 'Pérez','Magaña','2000-05-28','Valladolid','25','Roma Norte', '06700','Cuauhtemoc','CDMX','5512412621','juanperez@gmail.com','pwd123',1 )

INSERT INTO Paciente
VALUES ('CURPPRUEBAA112233',1.75,76,'A+','Paracetamol','Diabetes')


INSERT INTO Usuario 
VALUES ('CURPPRUEBAA123456','Andres', 'Juarez','Ortuño','2000-05-08','Colima','25','Roma Norte', '06700','Cuauhtemoc','CDMX','5512412621','andresjuarez@gmail.com','pwd123',1 )

INSERT INTO Paciente
VALUES ('CURPPRUEBAA123456',1.78,76,'A+','Paracetamol','Hipertension')



INSERT INTO Usuario 
VALUES ('CURPPRUEBAA223344','Miguel', 'López','Guzman','1970-07-28','Orfebreria','25','Oficios', '06700','Venustiano Carranza','CDMX','5514251425','miguellopez@gmail.com','pwd123',2 )




INSERT INTO TipoEmpleado
VALUES ('Doctor'), ('Recepcionista')

INSERT INTO Horario 
VALUES ('07:00:00','08:00:00'),
	('08:00:00','09:00:00'),
	('09:00:00','10:00:00'),
	('10:00:00','11:00:00'),
	('11:00:00','12:00:00'),
	('12:00:00','13:00:00'),
	('13:00:00','14:00:00'),
	('14:00:00','15:00:00'),
	('15:00:00','16:00:00'),
	('16:00:00','17:00:00'),
	('17:00:00','18:00:00'),
	('18:00:00','19:00:00'),
	('19:00:00','20:00:00'),
    ('07:00:00','14:00:00')

INSERT INTO EstatusCita
VALUES ('Agendada pendiente de pago'),
('Pagada pendiente por atender'),
('Cancelada Falta de pago'),
('Cancelada Paciente'),
('Cancelada Doctor'),
('Atendida'),
('No acudió')

INSERT INTO Empleado VALUES('CURPPRUEBAA223344','RFC223344',50000,1,14)



INSERT INTO Especialidad VALUES ('Cardiologo',700)
INSERT INTO Especialidad VALUES ('Nefrologo',900)
INSERT INTO Especialidad VALUES ('Dermatologo', 850);
INSERT INTO Especialidad VALUES ('Pediatra', 950);
INSERT INTO Especialidad VALUES ('Ginecologo', 1000);
INSERT INTO Especialidad VALUES ('Ortopedista', 1100);
INSERT INTO Especialidad VALUES ('Oftalmologo', 900);
INSERT INTO Especialidad VALUES ('Psiquiatra', 1300);
INSERT INTO Especialidad VALUES ('Endocrinologo', 1150);

INSERT INTO Medico VALUES ('CEDPRUEBA223344',1,1)

INSERT INTO Consultorio VALUES (110,1,3)

INSERT INTO Medicamento
VALUES('Loratadina', 'LOTEPRUEBA1', 100, 200, '2030-05-28')

INSERT INTO Factura 
VALUES ('2025-06-20','Primera cita del sistema','Pagada')

INSERT INTO Factura 
VALUES ('2025-06-20','Segunda cita del sistema','Pagada')

SELECT * FROM Cita

INSERT INTO Cita 
VALUES (1,2,1,'2025-06-28','2025-06-20',1,1,2)


INSERT INTO Usuario 
VALUES ('CURPPRUEBAA445566','Juan', 'Obrador','Peña','1970-08-28','Talabarteros','25','Oficios', '06700','Venustiano Carranza','CDMX','5514251425','juanobrador@gmail.com','pwd123',2 )

INSERT INTO Empleado VALUES('CURPPRUEBAA445566','RFC445566',50000,1,14)

INSERT INTO Medico VALUES ('CEDPRUEBA445566',2,2)


INSERT INTO Usuario 
VALUES ('CURPPRUEBAA224466','Miguel', 'Bernard','Carrasco','1970-01-28','Alfareros','25','Oficios', '06700','Venustiano Carranza','CDMX','5514251425','miguelbernard@gmail.com','pwd123',2 )

INSERT INTO Empleado VALUES('CURPPRUEBAA224466','RFC224466',50000,1,14)

INSERT INTO Medico VALUES ('CEDPRUEBA224466',3,2)



--Folio_Cita, ID_Paciente  , ID_Horario , ID_Medico , Fecha_Cita , Fecha_Reservacion, ID_Factura  ,ID_Consultorio  ,ID_EstatusCita  
--Cita en el pasado
INSERT INTO Cita 
VALUES (1,1,3,'2024-06-28','2025-06-20',2,1,2)

--Cita en mas de 3 meses
INSERT INTO Cita 
VALUES (2,3,3,'2027-06-28','2025-06-27',2,1,2)

--Cita en menos de 48hrs
INSERT INTO Cita 
VALUES (2,3,3,'2025-06-28','2025-06-27',2,1,2)

--Cita fuera del horario del doctor
INSERT INTO Cita 
VALUES (2,13,3,'2025-06-28','2025-06-20',2,1,2)

INSERT INTO Receta VALUES 
('2025-06-28','Alergia al polvo','Alergias','Loratadina cada que se presente un evento de alergia',1)

INSERT INTO DetalleReceta VALUES (1,1)

INSERT INTO Pago VALUES (1,'Tarjeta de débito','2025-06-21',700,700,'Pagado')