
create procedure SP_CANCELACION_CITA
	@folio int,
	@tipoUsuario int
as
	begin
		
		declare @costoCita DECIMAL(10,2);

		set @costoCita = (select Costo from Especialistas where ID = ( select ID_Medico from Cita where Folio_Cita=@folio ) );

		if ( @tipoUsuario = 2 ) begin
			update Cita set Monto_Devuelto = @costoCita, ID_EstatusCita = 5 where Folio_Cita = @folio;
		end
		else begin

			declare @fechaHoy datetime, @fechaCita datetime;
			declare @horaAntelacion float;

			set @fechaHoy = cast( (select getdate()) as datetime );
			set @fechaCita = cast( (select Fecha_Cita from Cita where Folio_Cita=@folio ) as datetime);

			set @horaAntelacion = datediff ( minute, @fechaHoy, @fechaCita )/60.0;

			if ( @horaAntelacion >= 48 ) begin
				update Cita set Monto_Devuelto = @costoCita where Folio_Cita = @folio;
			end
			else if ( @horaAntelacion < 48 and @horaAntelacion >= 24 ) begin
				update Cita set Monto_Devuelto = @costoCita*0.5 where Folio_Cita = @folio;
			end
			else begin
				update Cita set Monto_Devuelto = 0 where Folio_Cita = @folio;
			end

			update Cita set ID_EstatusCita = 4 where Folio_Cita = @folio;
		end
	end

	GO
	grant exec on SP_CANCELACION_CITA to userapela--user