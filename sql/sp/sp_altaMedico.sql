
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
	@tipoEspedialidad int,
	@RFC NVARCHAR(13),
	@Sueldo DECIMAL(10,2),
	@idHorario int
as
	begin

		begin tran;
			begin try;
				insert into Usuario
					values (@CURP, @Nombre, @Apellido_P, @Apellido_M, @Fecha_Nac, @Calle, @Numero, @Colonia, @Codig_P, @Ciudad, @Estado, @Telefono, @Email, @Pwd, 2);

				insert into Empleado (CURP, RFC, Sueldo, ID_TIpoEmpleado, ID_Horario)
					values ( @CURP, @RFC, @Sueldo, 1, @idHorario )

				declare @idEmpleado int;

				set @idEmpleado = (select ID_Empleado from Empleado where CURP = @CURP);

				insert into Medico (Cedula_Pro, ID_Empleado, ID_Especialidad)
					values ( @Cedula_Pro, @idEmpleado,  @tipoEspedialidad);

				commit;
			end try
			begin catch
				rollback transaction;
			end catch;
		-- 2--empleado 1--paciente select * from TipoUsuario
		-- 1-doctor 2-recpionista select * from TipoEmpleado
	end


	GO
	grant exec on SP_ALTA_MEDICO to --user



