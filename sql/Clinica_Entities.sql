CREATE DATABASE Clinica
GO

USE Clinica
GO

CREATE TABLE TipoUsuario (
    ID_TipoUsuario INT PRIMARY KEY IDENTITY(1,1),
    TipoUsuario NVARCHAR(50) NOT NULL
);

CREATE TABLE Usuario (
    CURP NVARCHAR(18) PRIMARY KEY ,
    Nombre NVARCHAR(50) NOT NULL,
    Apellido_P NVARCHAR(50) NOT NULL,
    Apellido_M NVARCHAR(50),
    Fecha_Nac DATE NOT NULL,
    Calle NVARCHAR(100),
    Numero NVARCHAR(10),
    Colonia NVARCHAR(50) NOT NULL,
    Codig_P NVARCHAR(10),
    Ciudad NVARCHAR(50) NOT NULL,
    Estado NVARCHAR(50),
    Telefono NVARCHAR(15) NOT NULL,
    Email NVARCHAR(100) NOT NULL UNIQUE ,
    Pwd NVARCHAR(30) NOT NULL,
    ID_TipoUsuario INT FOREIGN KEY REFERENCES TipoUsuario(ID_TipoUsuario),
    Estatus NVARCHAR(10) NOT NULL DEFAULT 'Activo'
);

CREATE TABLE Paciente (
    ID_Paciente INT PRIMARY KEY IDENTITY(1,1),
    CURP NVARCHAR(18) FOREIGN KEY REFERENCES Usuario(CURP),
    Estatura DECIMAL(5,2),
    Peso DECIMAL(5,2),
    Tipo_Sangre NVARCHAR(5),
    Alergia NVARCHAR(MAX),
    Padecimientos NVARCHAR(MAX)
);

CREATE TABLE TipoEmpleado (
    ID_TipoEmpleado INT PRIMARY KEY IDENTITY(1,1),
    TipoEmpleado NVARCHAR(50) NOT NULL
);

CREATE TABLE Horario(
    ID_Horario INT PRIMARY KEY IDENTITY(1,1),
    Inicio_Horario TIME,
    Fin_Horario TIME
);

CREATE TABLE Empleado (
    ID_Empleado INT PRIMARY KEY IDENTITY(1,1),
    CURP NVARCHAR(18) FOREIGN KEY REFERENCES Usuario(CURP),
    RFC NVARCHAR(13) ,
    Sueldo DECIMAL(10,2),
    ID_TipoEmpleado INT FOREIGN KEY REFERENCES TipoEmpleado(ID_TipoEmpleado),
	ID_Horario INT FOREIGN KEY REFERENCES Horario(ID_Horario)
);

CREATE TABLE Especialidad (
    ID_Especialidad INT PRIMARY KEY IDENTITY(1,1),
    Nombre NVARCHAR(100) NOT NULL,
    Costo_Consulta DECIMAL(10,2)
);



CREATE TABLE Medico (
    ID_Medico INT PRIMARY KEY IDENTITY(1,1),
    Cedula_Pro NVARCHAR(20) NOT NULL,
    ID_Empleado INT FOREIGN KEY REFERENCES Empleado(ID_Empleado),
    ID_Especialidad INT FOREIGN KEY REFERENCES Especialidad(ID_Especialidad),
    
);

CREATE TABLE Recepcionista (
    ID_Recepcionista INT PRIMARY KEY IDENTITY(1,1),
    ID_Empleado INT FOREIGN KEY REFERENCES Empleado(ID_Empleado)
);

CREATE TABLE Consultorio (
    ID_Consultorio INT PRIMARY KEY IDENTITY(1,1),
    Numero INT,
    Piso INT,
    Capacidad INT
);

CREATE TABLE Factura (
    ID_Factura INT PRIMARY KEY IDENTITY(1,1),
    Fecha DATE,
    Concepto NVARCHAR(100),
    Estatus NVARCHAR(50)
);

CREATE TABLE EstatusCita (
    ID_EstatusCita INT PRIMARY KEY IDENTITY(1,1),
    EstatusCita NVARCHAR(30)
)

CREATE TABLE Cita (
    Folio_Cita INT PRIMARY KEY IDENTITY(1,1),
    ID_Paciente INT FOREIGN KEY REFERENCES Paciente(ID_Paciente),
	ID_Horario INT FOREIGN KEY References Horario(ID_Horario),
	ID_Medico INT FOREIGN KEY References Medico(ID_Medico),
    Fecha_Cita DATE NOT NULL,
    Fecha_Reservacion DATETIME NOT NULL,
    ID_Factura INT FOREIGN KEY REFERENCES Factura(ID_Factura),
    ID_Consultorio INT FOREIGN KEY REFERENCES Consultorio(ID_Consultorio),
    ID_EstatusCita INT FOREIGN KEY REFERENCES EstatusCita (ID_EstatusCita),
    Monto_Devuelto MONEY NOT NULL DEFAULT 0,
	CONSTRAINT UQ_HorarioConsulta_Fecha UNIQUE (ID_Horario, ID_Medico, Fecha_Cita)
);


CREATE TABLE Receta (
    Folio_Receta INT PRIMARY KEY IDENTITY(1,1),
    Fecha DATE,
    Observaciones NVARCHAR(MAX),
    Diagnostico NVARCHAR(MAX),
    Tratamiento NVARCHAR(MAX),
    Folio_Cita INT FOREIGN KEY REFERENCES Cita(Folio_Cita)
);

CREATE TABLE Medicamento (
    ID_Medicamento INT PRIMARY KEY IDENTITY(1,1),
    Nombre NVARCHAR(100),
    Lote NVARCHAR(50),
    Costo DECIMAL(10,2),
    Precio DECIMAL(10,2),
    Caducidad DATE NOT NULL
);

CREATE TABLE DetalleReceta (
    Folio_Receta INT,
    ID_Medicamento INT,
    PRIMARY KEY (Folio_Receta, ID_Medicamento),
    FOREIGN KEY (Folio_Receta) REFERENCES Receta(Folio_Receta),
    FOREIGN KEY (ID_Medicamento) REFERENCES Medicamento(ID_Medicamento)
);

CREATE TABLE Servicio (
    ID_Servicio INT PRIMARY KEY IDENTITY(1,1),
    Nombre NVARCHAR(100) NOT NULL,
    Descripcion NVARCHAR(MAX),
    Costo DECIMAL(10,2)
);

CREATE TABLE Venta (
    ID_Venta INT PRIMARY KEY IDENTITY(1,1),
    ID_Recepcionista INT FOREIGN KEY REFERENCES Recepcionista(ID_Recepcionista),
    FechaVenta DATE,
    Concepto NVARCHAR(100)
);

CREATE TABLE Pago (
    ID_Pago INT PRIMARY KEY IDENTITY(1,1),
    ID_Factura INT FOREIGN KEY REFERENCES Factura(ID_Factura),
	ID_Venta INT FOREIGN KEY REFERENCES Venta(ID_Venta),
    Metodo_Pago NVARCHAR(50),
    Fecha_Pago DATE NOT NULL,
    Total DECIMAL(10,2),
    Estatus_Pago NVARCHAR(50) NOT NULL
);



CREATE TABLE DetalleMedicamento (
    ID_Venta INT,
    ID_Medicamento INT,
    Cantidad INT,
    PRIMARY KEY (ID_Venta, ID_Medicamento),
    FOREIGN KEY (ID_Venta) REFERENCES Venta(ID_Venta),
    FOREIGN KEY (ID_Medicamento) REFERENCES Medicamento(ID_Medicamento),

);

CREATE TABLE DetalleServicio (
    ID_Venta INT,
    Cantidad INT,
    ID_Servicio INT,
    PRIMARY KEY (ID_Venta, ID_Servicio),
    FOREIGN KEY (ID_Venta) REFERENCES Venta(ID_Venta),
    FOREIGN KEY (ID_Servicio) REFERENCES Servicio(ID_Servicio)
);
