
create procedure SP_CANCELACION_CITA
	@folio int,
	@tipoUsuario int
as
	begin
		
		declare @costoCita DECIMAL(10,2), @ID_EstatusCita int;

		set @costoCita = (select Costo from Especialistas where ID = ( select ID_Medico from Cita where Folio_Cita=@folio ) );

		set @ID_EstatusCita = (select ID_EstatusCita from Cita where Folio_Cita=@folio);

		if ( @ID_EstatusCita = 1 ) begin
			if ( @tipoUsuario = 2 ) begin
				update Cita set Monto_Devuelto = 0.0, ID_EstatusCita = 5 where Folio_Cita = @folio;
			end else begin
				update Cita set Monto_Devuelto = 0.0, ID_EstatusCita = 4 where Folio_Cita = @folio;
			end
		end
		else if ( @tipoUsuario = 2 ) begin
			update Cita set Monto_Devuelto = cast(@costoCita as money), ID_EstatusCita = 5 where Folio_Cita = @folio;
		end
		else begin

			declare @fechaHoy datetime, @fechaFinal datetime, @fechaCita date, @horaCita time;
			declare @horaAntelacion float;

			set @fechaHoy = cast( (select getdate()) as datetime );

			set @fechaCita = (select Fecha_Cita from Cita where Folio_Cita=@folio);
			set @horaCita = (select h.Inicio_Horario from Cita c join Horario h on c.ID_Horario=h.ID_Horario where Folio_Cita=@folio);

			set @fechaFinal = cast(@fechaCita as datetime) + cast(@horaCita as datetime);

			set @horaAntelacion = datediff ( minute, @fechaHoy, @fechaFinal )/60.0;

			if ( @horaAntelacion >= 48.0 ) begin
				update Cita set Monto_Devuelto = cast(@costoCita as money) where Folio_Cita = @folio;
			end
			else if ( @horaAntelacion < 48.0 and @horaAntelacion >= 24.0 ) begin
				update Cita set Monto_Devuelto = cast(@costoCita as money) *0.5 where Folio_Cita = @folio;
			end
			else begin
				update Cita set Monto_Devuelto = 0.0 where Folio_Cita = @folio;
			end

			update Cita set ID_EstatusCita = 4 where Folio_Cita = @folio;
		end
	end

	GO
	grant exec on SP_CANCELACION_CITA to userapela--user