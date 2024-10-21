CREATE DATABASE preguntar;

USE preguntar;

CREATE TABLE usuarios (
                          id INT AUTO_INCREMENT PRIMARY KEY,
                          nombre_completo VARCHAR(100) NOT NULL,
                          fecha_nacimiento DATE NOT NULL,
                          usuario VARCHAR(50) NOT NULL UNIQUE,
                          email VARCHAR(100) NOT NULL UNIQUE,
                          contraseña VARCHAR(255) NOT NULL,
                          ubicacion VARCHAR(50) NOT NULL,
                          foto VARCHAR(255),
                          sexo ENUM('masculino', 'femenino', 'otro') NOT NULL,
                          fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                          verify_token VARCHAR(255) NOT NULL,
                          esta_verificado BOOLEAN DEFAULT FALSE
);

INSERT INTO `usuarios` (`id`, `nombre_completo`, `fecha_nacimiento`, `usuario`, `email`, `contraseña`, `ubicacion`, `foto`, `sexo`, `fecha_registro`, `verify_token`, `esta_verificado`) 
VALUES (NULL, 'Juan Perez', '2000-1-01', 'test', 'test@test.com', 'test', 'Argentina', 'test.png', 'masculino', current_timestamp(), '', '0');