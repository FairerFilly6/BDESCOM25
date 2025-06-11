
create function obtenerCitasPendientes ( @fechaBuscada date ) returns table
as return 
(
	select * from CitasMedico where @fechaBuscada=Fecha_Cita
)

create function  obtenerEdadPaciente ( @fech_nac date ) returns int
as 
begin

	return datediff(mm, @fech_nac, getdate())/12

end

create function obtenerDisponibilidadMedico( @idMedico int, @fecha date ) returns table
as return (
	select hd.Horario, 
		case
			when cm.Medico is null then 'Disponible'
			else 'Ocupado'
		end as Disponibilidad
		from HorariosDia hd left join CitasMedico cm on hd.Horario=cm.Horario and ID_Medico=@idMedico and cm.Fecha_Cita=@fecha
)