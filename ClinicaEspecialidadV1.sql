USE ClinicaEspecialidadV1

CREATE TABLE TipoUsuario (
    ID_TipoUsuario INT PRIMARY KEY IDENTITY(1,1),
    TipoUsuario NVARCHAR(50)
);

CREATE TABLE Usuario (
    CURP NVARCHAR(18) PRIMARY KEY ,
    Nombre NVARCHAR(50),
    Apellido_P NVARCHAR(50),
    Apellido_M NVARCHAR(50),
    Fecha_Nac DATE,
    Calle NVARCHAR(100),
    Numero NVARCHAR(10),
    Colonia NVARCHAR(50),
    Codig_P NVARCHAR(10),
    Ciudad NVARCHAR(50),
    Estado NVARCHAR(50),
    Telefono NVARCHAR(15),
    Email NVARCHAR(100),
    ID_TipoUsuario INT FOREIGN KEY REFERENCES TipoUsuario(ID_TipoUsuario)
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
    TipoEmpleado NVARCHAR(50)
);

CREATE TABLE Empleado (
    ID_Empleado INT PRIMARY KEY IDENTITY(1,1),
    CURP NVARCHAR(18) FOREIGN KEY REFERENCES Usuario(CURP),
    RFC NVARCHAR(13),
    Sueldo DECIMAL(10,2),
    ID_TipoEmpleado INT FOREIGN KEY REFERENCES TipoEmpleado(ID_TipoEmpleado)
);

CREATE TABLE Especialidad (
    ID_Especialidad INT PRIMARY KEY IDENTITY(1,1),
    Nombre NVARCHAR(100),
    Costo_Consulta DECIMAL(10,2)
);

CREATE TABLE Medico (
    ID_Medico INT PRIMARY KEY IDENTITY(1,1),
    Cedula_Pro NVARCHAR(20),
    ID_Empleado INT FOREIGN KEY REFERENCES Empleado(ID_Empleado),
    ID_Especialidad INT FOREIGN KEY REFERENCES Especialidad(ID_Especialidad)
);

CREATE TABLE Recepcionista (
    ID_Recepcionista INT PRIMARY KEY IDENTITY(1,1),
    ID_Empleado INT FOREIGN KEY REFERENCES Empleado(ID_Empleado),
    Horario NVARCHAR(50)
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

CREATE TABLE Cita (
    Folio_Cita INT PRIMARY KEY IDENTITY(1,1),
    ID_Paciente INT FOREIGN KEY REFERENCES Paciente(ID_Paciente),
    ID_Medico INT FOREIGN KEY REFERENCES Medico(ID_Medico),
    ID_Especialidad INT FOREIGN KEY REFERENCES Especialidad(ID_Especialidad),
    Fecha_Cita DATE,
    Fecha_Reservacion DATE,
    ID_Factura INT FOREIGN KEY REFERENCES Factura(ID_Factura),
    ID_Consultorio INT FOREIGN KEY REFERENCES Consultorio(ID_Consultorio)
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
    Caducidad DATE
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
    Nombre NVARCHAR(100),
    Descripcion NVARCHAR(MAX),
    Costo DECIMAL(10,2)
);

CREATE TABLE Venta (
    ID_Venta INT PRIMARY KEY IDENTITY(1,1),
    ID_Recepcionista INT FOREIGN KEY REFERENCES Recepcionista(ID_Recepcionista),
    ID_Factura INT FOREIGN KEY REFERENCES Factura(ID_Factura),
    FechaVenta DATE,
    Concepto NVARCHAR(100)
);

CREATE TABLE DetalleVenta (
    ID_Venta INT,
    ID_Medicamento INT,
    Cantidad INT,
    ID_Servicio INT,
    PRIMARY KEY (ID_Venta, ID_Medicamento, ID_Servicio),
    FOREIGN KEY (ID_Venta) REFERENCES Venta(ID_Venta),
    FOREIGN KEY (ID_Medicamento) REFERENCES Medicamento(ID_Medicamento),
    FOREIGN KEY (ID_Servicio) REFERENCES Servicio(ID_Servicio)
);

CREATE TABLE Pago (
    ID_Pago INT PRIMARY KEY IDENTITY(1,1),
    ID_Factura INT FOREIGN KEY REFERENCES Factura(ID_Factura),
    Metodo_Pago NVARCHAR(50),
    Fecha_Pago DATE,
    Monto DECIMAL(10,2),
    Total DECIMAL(10,2),
    Estatus_Pago NVARCHAR(50)
);