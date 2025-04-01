-- ==============================================
-- Configuración inicial
-- ==============================================
CREATE DATABASE IF NOT EXISTS idiomas_db
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE idiomas_db;

-- ==============================================
-- Tabla de recursos (base para herencia)
-- ==============================================
CREATE TABLE IF NOT EXISTS resources (
                                         id INT AUTO_INCREMENT PRIMARY KEY,
                                         name VARCHAR(255) NOT NULL,
                                         type ENUM('class', 'exam') NOT NULL,
                                         created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                                         INDEX idx_type (type),
                                         FULLTEXT INDEX idx_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==============================================
-- Tabla de clases (especialización)
-- ==============================================
CREATE TABLE IF NOT EXISTS classes (
                                       resource_id INT PRIMARY KEY,
                                       rating TINYINT UNSIGNED NOT NULL CHECK (rating BETWEEN 1 AND 5),
                                       FOREIGN KEY (resource_id)
                                           REFERENCES resources(id)
                                           ON DELETE CASCADE
                                           ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ==============================================
-- Tabla de exámenes (especialización)
-- ==============================================
CREATE TABLE IF NOT EXISTS exams (
                                     resource_id INT PRIMARY KEY,
                                     exam_type ENUM(
                                         'selección',
                                         'pregunta y respuesta',
                                         'completación'
                                         ) NOT NULL,
                                     FOREIGN KEY (resource_id)
                                         REFERENCES resources(id)
                                         ON DELETE CASCADE
                                         ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ==============================================
-- Datos iniciales (ejemplo del enunciado)
-- ==============================================
INSERT INTO resources (name, type) VALUES
                                       ('Vocabulario sobre Trabajo en Inglés', 'class'),
                                       ('Conversaciones de Trabajo en Inglés', 'class'),
                                       ('Trabajos y ocupaciones en Inglés', 'exam');

INSERT INTO classes (resource_id, rating) VALUES
                                              (1, 5),
                                              (2, 5);

INSERT INTO exams (resource_id, exam_type) VALUES
    (3, 'selección');

-- Datos de ejemplo para inglés
INSERT INTO resources (name, type) VALUES
                                       ('Inglés Básico: Saludos y Presentaciones', 'class'),
                                       ('Inglés Intermedio: Verbos Irregulares', 'class'),
                                       ('Inglés Avanzado: Phrasal Verbs', 'class'),
                                       ('Examen de Inglés Básico', 'exam'),
                                       ('Examen de Inglés Intermedio', 'exam'),
                                       ('Examen de Inglés Avanzado', 'exam');

INSERT INTO classes (resource_id, rating) VALUES
                                              (4, 4),
                                              (5, 5),
                                              (6, 4);

INSERT INTO exams (resource_id, exam_type) VALUES
                                               (7, 'selección'),
                                               (8, 'pregunta y respuesta'),
                                               (9, 'completación');

-- Datos de ejemplo para español
INSERT INTO resources (name, type) VALUES
                                       ('Español Básico: Artículos y Género', 'class'),
                                       ('Español Intermedio: Verbos Ser y Estar', 'class'),
                                       ('Español Avanzado: Modo Subjuntivo', 'class'),
                                       ('Examen de Español Básico', 'exam'),
                                       ('Examen de Español Intermedio', 'exam');

INSERT INTO classes (resource_id, rating) VALUES
                                              (10, 5),
                                              (11, 4),
                                              (12, 3);

INSERT INTO exams (resource_id, exam_type) VALUES
                                               (13, 'selección'),
                                               (14, 'pregunta y respuesta');

-- Datos de ejemplo para francés
INSERT INTO resources (name, type) VALUES
                                       ('Francés Básico: Pronombres Personales', 'class'),
                                       ('Francés Intermedio: Conjugación de Verbos', 'class'),
                                       ('Examen de Francés Básico', 'exam');

INSERT INTO classes (resource_id, rating) VALUES
                                              (15, 4),
                                              (16, 5);

INSERT INTO exams (resource_id, exam_type) VALUES
    (17, 'completación');

-- Datos de ejemplo para alemán
INSERT INTO resources (name, type) VALUES
                                       ('Alemán Básico: Artículos Definidos', 'class'),
                                       ('Alemán Intermedio: Casos Gramaticales', 'class'),
                                       ('Examen de Alemán Básico', 'exam');

INSERT INTO classes (resource_id, rating) VALUES
                                              (18, 3),
                                              (19, 4);

INSERT INTO exams (resource_id, exam_type) VALUES
    (20, 'selección');

-- Datos de ejemplo para italiano
INSERT INTO resources (name, type) VALUES
                                       ('Italiano Básico: Saludos Comunes', 'class'),
                                       ('Examen de Italiano Básico', 'exam');

INSERT INTO classes (resource_id, rating) VALUES
    (21, 5);

INSERT INTO exams (resource_id, exam_type) VALUES
    (22, 'pregunta y respuesta');

-- Datos de ejemplo para portugués
INSERT INTO resources (name, type) VALUES
                                       ('Portugués Básico: Diferencias con Español', 'class'),
                                       ('Portugués Intermedio: Verbos Regulares', 'class'),
                                       ('Examen de Portugués Básico', 'exam');

INSERT INTO classes (resource_id, rating) VALUES
                                              (23, 4),
                                              (24, 5);

INSERT INTO exams (resource_id, exam_type) VALUES
    (25, 'completación');