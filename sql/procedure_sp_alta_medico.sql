select * from Medico

select * from Usuario

grant exec on SP_ALTA_MEDICO to userapela
grant exec on SP_ALTA_RECEPCIONISTA to userapela

select * from Especialidad

create procedure SP_ALTA_MEDICO
	@CURP NVARCHAR(18),
    @Nombre NVARCHAR(50),
    @Apellido_P NVARCHAR(50),
    @Apellido_M NVARCHAR(50) =  null,
    @Fecha_Nac DATE,
    @Calle NVARCHAR(100) =  null,
    @Numero NVARCHAR(10) = null,
    @Colonia NVARCHAR(50),
    @Codig_P NVARCHAR(10) = null,
    @Ciudad NVARCHAR(50),
    @Estado NVARCHAR(50) = null,
    @Telefono NVARCHAR(15),
    @Email NVARCHAR(100),
    @Pwd NVARCHAR(30),
	@Cedula_Pro NVARCHAR(18),
	@tipoEspedialidad int
as
	begin
		insert into Usuario
			values (@CURP, @Nombre, @Apellido_P, @Apellido_M, @Fecha_Nac, @Calle, @Numero, @Colonia, @Codig_P, @Ciudad, @Estado, @Telefono, @Email, @Pwd, 2);

		insert into Medico (Cedula_Pro, ID_Empleado, ID_Especialidad)
			values ( @Cedula_Pro, 1,  @tipoEspedialidad);
		-- 2--empleado 1--paciente select * from TipoUsuario
		-- 1-doctor 2-recpionista select * from TipoEmpleado
	end



create procedure SP_ALTA_RECEPCIONISTA
	@CURP NVARCHAR(18),
    @Nombre NVARCHAR(50),
    @Apellido_P NVARCHAR(50),
    @Apellido_M NVARCHAR(50) =  null,
    @Fecha_Nac DATE,
    @Calle NVARCHAR(100) =  null,
    @Numero NVARCHAR(10) = null,
    @Colonia NVARCHAR(50),
    @Codig_P NVARCHAR(10) = null,
    @Ciudad NVARCHAR(50),
    @Estado NVARCHAR(50) = null,
    @Telefono NVARCHAR(15),
    @Email NVARCHAR(100),
    @Pwd NVARCHAR(30),
	@RFC NVARCHAR(13),
	@Sueldo DECIMAL(10,2),
	@idHorario int
as
	begin
	begin tran;
		begin try
			insert into Usuario
				values (@CURP, @Nombre, @Apellido_P, @Apellido_M, @Fecha_Nac, @Calle, @Numero, @Colonia, @Codig_P, @Ciudad, @Estado, @Telefono, @Email, @Pwd, 2);

			insert into Empleado ( CURP, RFC, Sueldo, ID_TipoEmpleado, ID_Horario )
				values (@CURP, @RFC, @Sueldo, 2, @idHorario);

			declare @idEmpleado int;

			set @idEmpleado = (select ID_Empleado from Empleado where CURP = @CURP);

			insert into Recepcionista (ID_Empleado)
				values ( @idEmpleado);
			commit;
		end try
		begin catch
			rollback transaction;
		end catch;
		-- 2--empleado 1--paciente select * from TipoUsuario
		-- 1-doctor 2-recpionista select * from TipoEmpleado
	end