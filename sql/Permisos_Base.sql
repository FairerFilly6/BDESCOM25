-- Crea el usuario y su password
CREATE LOGIN userapela WITH PASSWORD = 'userapela';


USE Clinica;
GO

-- Crear usuario en la base, si no existe
CREATE USER userapela FOR LOGIN userapela;

go

-- Concede control total sobre la base
grant CONTROL to userapela