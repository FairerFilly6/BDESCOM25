USE ClinicaEspecialidadV1

INSERT INTO TipoUsuario VALUES ('Paciente')

INSERT INTO TipoUsuario VALUES ('Empleado')

INSERT INTO Usuario 
VALUES ('CURPPRUEBAA112233','Juan', 'Pérez','Magaña','2000-05-28','Valladolid','25','Roma Norte', '06700','Cuauhtemoc','CDMX','5512412621','juanperez@gmail.com','pwd123',1 )

INSERT INTO Paciente
VALUES ('CURPPRUEBAA112233',1.75,76,'A+','Paracetamol','Diabetes')

INSERT INTO Usuario 
VALUES ('CURPPRUEBAA223344','Miguel', 'López','Guzman','1970-07-28','Orfebreria','25','Oficios', '06700','Venustiano Carranza','CDMX','5514251425','miguellopez@gmail.com','pwd123',2 )

INSERT INTO TipoEmpleado
VALUES ('Doctor'), ('Recepcionista')

INSERT INTO Empleado VALUES('CURPPRUEBAA223344','RFC223344',50000,1)

INSERT INTO Especialidad VALUES ('Cardiologo',700)

INSERT INTO Medico VALUES ('CEDPRUEBA223344',1,1)

INSERT INTO Consultorio VALUES (110,1,3)

INSERT INTO Medicamento
VALUES('Loratadina', 'LOTEPRUEBA1', 100, 200, '2030-05-28')

INSERT INTO Factura 
VALUES ('2025-06-20','Primera cita del sistema','Pagada')

INSERT INTO Cita 
VALUES (1,2,1,'2025-06-28','2025-06-20',1,1)

INSERT INTO Receta VALUES 
('2025-06-28','Alergia al polvo','Alergias','Loratadina cada que se presente un evento de alergia',1)

INSERT INTO DetalleReceta VALUES (1,1)

INSERT INTO Pago VALUES (1,'Tarjeta de débito','2025-06-21',700,700,'Pagado')