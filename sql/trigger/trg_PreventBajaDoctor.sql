
create trigger trg_PreventBajaMedico on Usuario instead of UPDATE as

begin
	if exists (
		select 1 from inserted i join deleted d on i.CURP=d.CURP where i.Estatus<>d.Estatus
	)
	begin
		if exists (
			select 1 from inserted u left join Empleado e on u.CURP=e.CURP
				left join Medico m on e.ID_Empleado=m.ID_Empleado
				left join Cita c on c.ID_Medico = m.ID_Medico
				where c.ID_EstatusCita in ( 1, 2 )
		) begin
			RAISERROR('No está permitido dar de baja a un doctor si tiene una cita pendiente.', 16, 1);
			rollback transaction;
			return;
		end
	end
	else if exists (
		select 1 from inserted i join deleted d on i.CURP=d.CURP
			where i.Nombre<>d.Nombre or i.Apellido_M<>d.Apellido_M or
				i.Apellido_P<>d.Apellido_P or i.Fecha_Nac<>d.Fecha_Nac or
				i.Calle<>d.Calle or i.Numero<>d.Numero or
				i.Colonia<>d.Colonia or i.Codig_P<>d.Codig_P or
				i.Ciudad<>d.Ciudad or i.Estado<>d.Estado or
				i.Telefono<>d.Telefono or i.Pwd<>d.Pwd
	) begin
		update u
		set

			u.Nombre = i.Nombre,
			u.Apellido_M = i.Apellido_M,
			u.Apellido_P = i.Apellido_P,
			u.Fecha_Nac = i.Fecha_Nac,
			u.Calle = i.Calle,
			u.Numero = i.Numero,
			u.Colonia = i.Colonia,
			u.Codig_P = i.Codig_P,
			u.Ciudad = i.Ciudad,
			u.Estado = i.Estado,
			u.Telefono = i.Telefono,
			u.Pwd = i.Pwd
		from Usuario u join inserted i on u.CURP=i.CURP;
	end
	else begin
		update u set u.Estatus=i.Estatus from Usuario u join inserted i on u.CURP=i.CURP;
	end

end
