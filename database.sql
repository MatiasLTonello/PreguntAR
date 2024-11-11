DROP DATABASE IF EXISTS preguntar;

CREATE DATABASE preguntar;

USE preguntar;

CREATE TABLE roles (
                       id INT AUTO_INCREMENT PRIMARY KEY,
                       descripcion VARCHAR(50) NOT NULL
);

CREATE TABLE usuarios (
                          id INT AUTO_INCREMENT PRIMARY KEY,
                          nombre_completo VARCHAR(100) NOT NULL,
                          fecha_nacimiento DATE NOT NULL,
                          usuario VARCHAR(50) NOT NULL UNIQUE,
                          email VARCHAR(100) NOT NULL UNIQUE,
                          contraseña VARCHAR(255) NOT NULL,
                          ubicacion VARCHAR(50) NOT NULL,
                          id_rol INT,
                          foto VARCHAR(255),
                          sexo ENUM ('masculino', 'femenino', 'otro') NOT NULL,
                          fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                          verify_token VARCHAR(255) NOT NULL,
                          esta_verificado BOOLEAN DEFAULT FALSE,
                          nivel INT DEFAULT 1,
                          FOREIGN KEY (id_rol) REFERENCES roles (id)
);

CREATE TABLE partidas (
                          id INT AUTO_INCREMENT PRIMARY KEY,
                          puntaje INT,
                          fecha DATE,
                          id_usuario INT,
                          terminada BOOLEAN,
                          FOREIGN KEY (id_usuario) REFERENCES usuarios (id)
);

CREATE TABLE categorias (
                            id INT AUTO_INCREMENT PRIMARY KEY,
                            nombre VARCHAR(50) NOT NULL,
                            color VARCHAR(50) NOT NULL
);

CREATE TABLE preguntas (
                            id INT AUTO_INCREMENT PRIMARY KEY,
                            pregunta VARCHAR(255) NOT NULL,
                            id_categoria INT,
                            apariciones INT DEFAULT 0,
                            correctas INT DEFAULT 0,
                            estado ENUM ('aprobada', 'desaprobada', 'reportada', 'eliminada', 'sugerida') NOT NULL,
                            esta_eliminada BOOLEAN DEFAULT FALSE,
                            FOREIGN KEY (id_categoria) REFERENCES categorias (id)
);

CREATE TABLE opciones (
                          id INT AUTO_INCREMENT PRIMARY KEY,
                          opcion VARCHAR(255) NOT NULL,
                          es_correcta BOOLEAN,
                          id_pregunta INT,
                          FOREIGN KEY (id_pregunta) REFERENCES preguntas (id)
);

CREATE TABLE historial_usuarios_preguntas (
                                              id INT AUTO_INCREMENT PRIMARY KEY,
                                              id_usuario INT,
                                              id_pregunta INT,
                                              contesto_correctamente BOOLEAN,
                                              FOREIGN KEY (id_usuario) REFERENCES usuarios (id),
                                              FOREIGN KEY (id_pregunta) REFERENCES preguntas (id)
);

INSERT INTO roles (descripcion) VALUES
                                    ('admin'),
                                    ('editor'),
                                    ('jugador');

INSERT INTO usuarios (
    nombre_completo,
    fecha_nacimiento,
    usuario,
    email,
    contraseña,
    ubicacion,
    id_rol,
    foto,
    sexo,
    fecha_registro,
    verify_token,
    esta_verificado,
    nivel
) VALUES
      ('Juan Perez', '2000-1-01', 'test', 'test@test.com', '098f6bcd4621d373cade4e832627b4f6', 'Argentina', 2, 'test.png', 'masculino', current_timestamp(), '', '0', '1'), -- contraseña: test
      ('Sofia Morales', '1999-6-22', 'sofia', 'sofia@test.com', '098f6bcd4621d373cade4e832627b4f6', 'Argentina', 1, 'test.png', 'masculino', current_timestamp(), '', '0', '1'), -- contraseña: test
      ('Diego Rios', '2001-9-05', 'diego', 'diego@test.com', '098f6bcd4621d373cade4e832627b4f6', 'Argentina', 1, 'test.png', 'masculino', current_timestamp(), '', '0', '1'), -- contraseña: test
      ('Lucia Fernández', '2002-4-12', 'lucia', 'lucia@test.com', '098f6bcd4621d373cade4e832627b4f6', 'Argentina', 1, 'test.png', 'masculino', current_timestamp(), '', '0', '1'), -- contraseña: test
      ('Leo Arias', '2000-1-01', 'leo', 'correo@correo.com', '202cb962ac59075b964b07152d234b70' , 'Argentina', 1, 'test.png', 'masculino', current_timestamp(), '', '1', '1'); -- contraseña: 123

INSERT INTO categorias (nombre, color) VALUES
                                           ('Historia', '#ffce54'),
                                           ('Geografia', '#4a90e2'),
                                           ('Ciencia', '#55ba69'),
                                           ('Deportes', '#f58d42'),
                                           ('Arte y Literatura', '#eb3939'),
                                           ('Entretenimiento', '#ec5cca');

-- PREGUNTAS SOBRE ARGENTINA
INSERT INTO preguntas (pregunta, id_categoria) VALUES
                                                   ('¿Quién es considerado el padre de la patria en Argentina?', 1),
                                                   ('¿Cuál es el río más largo de Argentina?', 2),
                                                   ('¿En qué año se declaró la independencia de Argentina?', 1),
                                                   ('¿Quién es el máximo goleador de la historia de la selección argentina?', 4),
                                                   ('¿Cuál es la capital de Argentina?', 2),
                                                   ('¿Quién es el autor de la obra "Martín Fierro"?', 5),
                                                   ('¿Cuál es el plato típico de Argentina?', 6),
                                                   ('¿Cuál es el baile típico de Argentina?', 6),
                                                   ('¿Cuál es el deporte más popular en Argentina?', 4),
                                                   ('¿Cuál es el monumento más visitado de Argentina?', 5),
                                                   ('¿Cuál es la montaña más alta de Argentina?', 2),
                                                   ('¿Cuál es la moneda de Argentina?', 2),
                                                   ('¿En qué provincia se encuentra la ciudad de Bariloche?', 2),
                                                   ('¿Cuál es el nombre del escritor argentino que ganó el Premio Nobel de Química?', 5),
                                                   ('¿Qué presidente argentino firmó el Pacto de Olivos?', 1),
                                                   ('¿Cuál es el equipo de fútbol con más títulos de liga en Argentina?', 4),
                                                   ('¿Qué cantante es considerado uno de los máximos exponentes del rock argentino?', 6),
                                                   ('¿Qué año marcó el fin de la última dictadura militar en Argentina?', 1),
                                                   ('¿Cuál es la mayor reserva de agua dulce en Argentina?', 2),
                                                   ('¿En qué año ocurrió la Guerra de las Malvinas?', 1),
                                                   ('¿Cuál es la película argentina ganadora del Oscar?', 6),
                                                   ('¿Qué célebre escritora argentina escribió "Las viudas de los jueves"?', 5);


-- OPCIONES DE PREGUNTAS SOBRE ARGENTINA
INSERT INTO opciones (opcion, es_correcta, id_pregunta) VALUES
                                                            ('Manuel Belgrano', FALSE, 1),
                                                            ('José de San Martín', TRUE, 1),
                                                            ('Juan Manuel de Rosas', FALSE, 1),
                                                            ('Domingo Faustino Sarmiento', FALSE, 1),

                                                            ('Río Paraná', TRUE, 2),
                                                            ('Río Uruguay', FALSE, 2),
                                                            ('Río Colorado', FALSE, 2),
                                                            ('Río de la Plata', FALSE, 2),

                                                            ('1810', FALSE, 3),
                                                            ('1816', TRUE, 3),
                                                            ('1820', FALSE, 3),
                                                            ('1830', FALSE, 3),

                                                            ('Messi', FALSE, 4),
                                                            ('Maradona', TRUE, 4),
                                                            ('Batistuta', FALSE, 4),
                                                            ('Di Stefano', FALSE, 4),

                                                            ('Buenos Aires', TRUE, 5),
                                                            ('Córdoba', FALSE, 5),
                                                            ('Rosario', FALSE, 5),
                                                            ('Mendoza', FALSE, 5),

                                                            ('José Hernández', TRUE, 6),
                                                            ('Jorge Luis Borges', FALSE, 6),
                                                            ('Julio Cortázar', FALSE, 6),
                                                            ('Adolfo Bioy Casares', FALSE, 6),

                                                            ('Asado', TRUE, 7),
                                                            ('Milanesa', FALSE, 7),
                                                            ('Empanadas', FALSE, 7),
                                                            ('Locro', FALSE, 7),

                                                            ('Tango', TRUE, 8),
                                                            ('Cumbia', FALSE, 8),
                                                            ('Folklore', FALSE, 8),
                                                            ('Rock', FALSE, 8),

                                                            ('Fútbol', TRUE, 9),
                                                            ('Rugby', FALSE, 9),
                                                            ('Hockey', FALSE, 9),
                                                            ('Tenis', FALSE, 9),

                                                            ('Obelisco', TRUE, 10),
                                                            ('Casa Rosada', FALSE, 10),
                                                            ('Catedral de Buenos Aires', FALSE, 10),
                                                            ('Teatro Colón', FALSE, 10),

                                                            ('Aconcagua', TRUE, 11),
                                                            ('Ojos del Salado', FALSE, 11),
                                                            ('Lanín', FALSE, 11),
                                                            ('Fitz Roy', FALSE, 11),

                                                            ('Peso', TRUE, 12),
                                                            ('Dólar', FALSE, 12),
                                                            ('Euro', FALSE, 12),
                                                            ('Real', FALSE, 12),

                                                            ('Chubut', FALSE, 13),
                                                            ('Río Negro', TRUE, 13),
                                                            ('Neuquén', FALSE, 13),
                                                            ('Santa Cruz', FALSE, 13),

                                                            ('Bernardo Houssay', FALSE, 14),
                                                            ('Luis Federico Leloir', TRUE, 14),
                                                            ('César Milstein', FALSE, 14),
                                                            ('René Favaloro', FALSE, 14),

                                                            ('Raúl Alfonsín', TRUE, 15),
                                                            ('Carlos Menem', FALSE, 15),
                                                            ('Juan Domingo Perón', FALSE, 15),
                                                            ('Néstor Kirchner', FALSE, 15),

                                                            ('River Plate', TRUE, 16),
                                                            ('Boca Juniors', FALSE, 16),
                                                            ('Racing Club', FALSE, 16),
                                                            ('Independiente', FALSE, 16),

                                                            ('Charly García', TRUE, 17),
                                                            ('Gustavo Cerati', FALSE, 17),
                                                            ('Luis Alberto Spinetta', FALSE, 17),
                                                            ('Fito Páez', FALSE, 17),

                                                            ('1983', TRUE, 18),
                                                            ('1976', FALSE, 18),
                                                            ('1985', FALSE, 18),
                                                            ('1978', FALSE, 18),

                                                            ('Acuífero Guaraní', TRUE, 19),
                                                            ('Lago Nahuel Huapi', FALSE, 19),
                                                            ('Río de la Plata', FALSE, 19),
                                                            ('Laguna Mar Chiquita', FALSE, 19),

                                                            ('1982', TRUE, 20),
                                                            ('1980', FALSE, 20),
                                                            ('1978', FALSE, 20),
                                                            ('1985', FALSE, 20),

                                                            ('El Secreto de sus Ojos', TRUE, 21),
                                                            ('Relatos Salvajes', FALSE, 21),
                                                            ('La historia oficial', FALSE, 21),
                                                            ('El hijo de la novia', FALSE, 21),

                                                            ('Claudia Piñeiro', TRUE, 22),
                                                            ('Silvina Ocampo', FALSE, 22),
                                                            ('Alfonsina Storni', FALSE, 22),
                                                            ('Victoria Ocampo', FALSE, 22);
