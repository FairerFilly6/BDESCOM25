
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
			when cm.Horario is null then 'Disponible'
			else 'Ocupado'
		end as Disponibilidad
		from HorariosDia hd left join CitasMedico cm on hd.Horario=cm.Horario and ID_Medico=@idMedico and cm.Fecha_Cita=@fecha
)

CREATE FUNCTION fn_MedicoDisponible
(
    @ID_Medico INT,
    @Fecha_Cita DATE,
    @ID_Horario INT
)
RETURNS BIT
AS
BEGIN
    DECLARE @Disponible BIT

    IF EXISTS (
        SELECT 1
        FROM Cita
        WHERE ID_Medico = @ID_Medico
          AND Fecha_Cita = @Fecha_Cita
          AND ID_Horario = @ID_Horario
    )
    BEGIN
        SET @Disponible = 0 -- No disponible
    END
    ELSE
    BEGIN
        SET @Disponible = 1 -- Disponible
    END

    RETURN @Disponible
END
