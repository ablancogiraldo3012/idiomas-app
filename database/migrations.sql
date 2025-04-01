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