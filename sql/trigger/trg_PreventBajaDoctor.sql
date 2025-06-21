
create trigger trg_PreventBajaMedico on Usuario instead of UPDATE as

begin
	if exists 
	(
		select 1 from inserted i join deleted d on i.CURP=d.CURP where i.Estatus<>d.Estatus
	)
	begin
		if exists
		(
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

	update u set u.Estatus=i.Estatus from Usuario u join inserted i on u.CURP=i.CURP

end
