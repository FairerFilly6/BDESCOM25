USE Clinica

INSERT INTO Medicamento VALUES
	('Aspirina','BBBB',200,220,'12/10/2030'),
	('Loratadina','AAAA',100,120,'12/10/2030')

INSERT INTO Servicio VALUES
	('Curacion','Curacion de una herida menor',100),
	('Inyeccion','Aplicacion de inyeccion', 150)

INSERT INTO TipoUsuario VALUES
	('Paciente'),
	('Empleado')

	--recepcionista
INSERT INTO Usuario VALUES
	('CURPPRUEBAA112233','Juan', 'Pérez','Magaña','2000-05-28','Valladolid','25','Roma Norte', '06700','Cuauhtemoc','CDMX','5512412621','juanperez@gmail.com','pwd123',2, 'Activo')

insert into Empleado VALUES
	('CURPPRUEBAA112233','RFCRECE',7996,2,14)

insert into Recepcionista values (1)

INSERT INTO Usuario VALUES
	('CURPPRUEBAA123456','Andres', 'Juarez','Ortuño','2000-05-08','Colima','25','Roma Norte', '06700','Cuauhtemoc','CDMX','5512412621','andresjuarez@gmail.com','pwd123',1, 'Activo' )

INSERT INTO Paciente VALUES
	('CURPPRUEBAA123456',1.78,76,'A+','Paracetamol','Hipertension')



INSERT INTO Usuario VALUES
	('CURPPRUEBAA223344','Miguel', 'López','Guzman','1970-07-28','Orfebreria','25','Oficios', '06700','Venustiano Carranza','CDMX','5514251425','miguellopez@gmail.com','pwd123',2, 'Activo')

INSERT INTO Usuario VALUES
	('CURPPRUEBAA123457','Roberto', 'López','Guzman','1970-07-28','Orfebreria','25','Oficios', '06700','Venustiano Carranza','CDMX','5514251425','robertolopez@gmail.com','pwd123',2, 'Activo' )


INSERT INTO TipoEmpleado VALUES
	('Doctor'),
	('Recepcionista')

INSERT INTO Horario VALUES
	('07:00:00','08:00:00'),
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
    ('07:00:00','14:00:00'),
	('14:00:00','20:00:00')


INSERT INTO EstatusCita VALUES
	('Agendada pendiente de pago'),
	('Pagada pendiente por atender'),
	('Cancelada Falta de pago'),
	('Cancelada Paciente'),
	('Cancelada Doctor'),
	('Atendida'),
	('No acudió')


INSERT INTO Empleado VALUES('CURPPRUEBAA123457','RFC123457',50000,2,14)


INSERT INTO Especialidad VALUES
	('Cardiologo',700),
	('Nefrologo',900),
	('Dermatologo', 850),
	('Pediatra', 950),
	('Ginecologo', 1000),
	('Ortopedista', 1100),
	('Oftalmologo', 900),
	('Psiquiatra', 1300),
	('Endocrinologo', 1150)


INSERT INTO Consultorio VALUES (110,1,3)

INSERT INTO Factura VALUES ('12/06/2024', 'Venta de farmacia', 'Pagada')


INSERT INTO Recepcionista VALUES (3)

INSERT INTO Venta VALUES
	(2,'12/06/2025','Primer venta del sistema'),
	(2,'12/06/2025','Segunda venta del sistema')




	INSERT INTO DetalleMedicamento VALUES (1,1,1)
	INSERT INTO DetalleMedicamento VALUES (1,2,5)
	-- venta,medicamento, cantidad
	INSERT INTO DetalleMedicamento VALUES (2,1,5)
	INSERT INTO DetalleMedicamento VALUES (2,2,7)

	INSERT INTO DetalleServicio VALUES (1,1,1)
	INSERT INTO DetalleServicio VALUES (1,3,2)
	-- venta, cantidad, servicio
	INSERT INTO DetalleServicio VALUES (2,2,2)
	INSERT INTO DetalleServicio VALUES (2,5,1)

	--Crear el pago usando la funcion creada
INSERT INTO Pago VALUES (1,1,'Tarjeta de debito','12/06/25', dbo.obtenerTotalVenta (1),'Exito')

INSERT INTO Pago VALUES (1,2,'Tarjeta de debito','12/06/25', dbo.obtenerTotalVenta (2),'Segunda venta')




--Alta doctores
INSERT INTO Usuario 
VALUES ('CURPPRUEBAA654321','Jose', 'Abelardo','Guzman','1970-07-28','Orfebreria','5','Oficios', '06700','Venustiano Carranza','CDMX',
'5514251425','joseabelardo@gmail.com','pwd123',2 )

INSERT INTO Empleado VALUES('CURPPRUEBAA654321','RFC654321',50000,1,14)
--ced / idEmpleado / idEspecialidad
INSERT INTO Medico VALUES ('CEDPRUEBA654321',3,2)

INSERT INTO Usuario 
VALUES ('CURPPRUEBAA765432','Fernanda', 'Zamudio','Valdez','1978-07-28','Talabarteros','51','Oficios', '06700','Venustiano Carranza','CDMX',
'5514251425','fernandazamudio@gmail.com','pwd123',2 )

INSERT INTO Empleado VALUES('CURPPRUEBAA765432','RFC765432',50000,1,14)
--ced / idEmpleado / idEspecialidad
INSERT INTO Medico VALUES ('CEDPRUEBA765432',4,3)

INSERT INTO Usuario 
VALUES ('CURPPRUEBAA876543','Julion', 'Alvarez','Valdez','1988-07-28','Talabarteros','51','Oficios', '06700','Venustiano Carranza','CDMX',
'5514251425','julionalvarez@gmail.com','pwd123',2 )

INSERT INTO Empleado VALUES('CURPPRUEBAA876543','RFC876543',50000,1,14)
--ced / idEmpleado / idEspecialidad
INSERT INTO Medico VALUES ('CEDPRUEBA876543',5,4)

INSERT INTO Usuario 
VALUES ('CURPPRUEBAA987654','Marco', 'Ortega','Madrid','1988-07-28','Alfareros','51','Oficios', '06700','Venustiano Carranza','CDMX',
'5514251425','marcoortega@gmail.com','pwd123',2 )

INSERT INTO Empleado VALUES('CURPPRUEBAA987654','RFC987654',50000,1,14)
--ced / idEmpleado / idEspecialidad
INSERT INTO Medico VALUES ('CEDPRUEBA987654',6,5)


INSERT INTO Usuario 
VALUES ('CURPPRUEBAA111222','Sebastian', 'Madrid','Portillo','1988-07-28','Carteros','51','Oficios', '06700','Venustiano Carranza','CDMX',
'5514251425','sebastianmadrid@gmail.com','pwd123',2 )

INSERT INTO Empleado VALUES('CURPPRUEBAA111222','RFC111222',50000,1,14)
--ced / idEmpleado / idEspecialidad
INSERT INTO Medico VALUES ('CEDPRUEBA111222',7,6)


INSERT INTO Usuario 
VALUES ('CURPPRUEBAA222333','Alma', 'Perez','Parra','1988-07-28','Carteros','5','Oficios', '06700','Venustiano Carranza','CDMX',
'5514251425','almaperez@gmail.com','pwd123',2 )

INSERT INTO Empleado VALUES('CURPPRUEBAA222333','RFC222333',50000,1,15)
--ced / idEmpleado / idEspecialidad
INSERT INTO Medico VALUES ('CEDPRUEBA222333',9,2)


INSERT INTO Usuario 
VALUES ('CURPPRUEBAA333444','Gustavo', 'Obrador','Smith','1998-07-28','Carteros','5','Oficios', '06700','Venustiano Carranza','CDMX',
'5514251425','gustavoobrador@gmail.com','pwd123',2 )

INSERT INTO Empleado VALUES('CURPPRUEBAA333444','RFC333444',50000,1,15)
--ced / idEmpleado / idEspecialidad
INSERT INTO Medico VALUES ('CEDPRUEBA333444',10,3)