-- Base de datos TypeJobs
CREATE DATABASE typejobs;
USE typejobs;

-- Tabla usuarios simplificada
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(30) NOT NULL,
    apellido VARCHAR(30) NOT NULL,
    nomusuario VARCHAR(20) NOT NULL UNIQUE,
    email VARCHAR(50) NOT NULL UNIQUE,
    telefono VARCHAR(13) UNIQUE,
    contrasena VARCHAR(255) NOT NULL,
    tipo ENUM('CLIENTE', 'PROVEEDOR') DEFAULT 'CLIENTE',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insertar algunos usuarios de ejemplo
INSERT INTO usuarios (nombre, apellido, nomusuario, email, telefono, contrasena, tipo) VALUES
('Juan', 'Perez', 'juanp', 'juan@email.com', '+598 099 123 456', '123456', 'CLIENTE'),
('Maria', 'Garcia', 'mariag', 'maria@email.com', '+598 099 654 321', '123456', 'PROVEEDOR'),
('Carlos', 'Lopez', 'carlosl', 'carlos@email.com', '+598 099 789 012', '123456', 'CLIENTE');