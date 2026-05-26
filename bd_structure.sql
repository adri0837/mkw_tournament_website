-- Tabla de administradores
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de equipos
CREATE TABLE teams (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    logo_path VARCHAR(255),
    group_name ENUM('A','B','C','D') NOT NULL,
    slot_number INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de jugadores
CREATE TABLE players (
    id INT AUTO_INCREMENT PRIMARY KEY,
    team_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    character_icon VARCHAR(255),
    FOREIGN KEY (team_id) REFERENCES teams(id) ON DELETE CASCADE
);

-- Tabla de clasificación
CREATE TABLE standings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    team_id INT NOT NULL UNIQUE,
    points INT DEFAULT 0,
    played INT DEFAULT 0,
    wins INT DEFAULT 0,
    draws INT DEFAULT 0,
    losses INT DEFAULT 0,
    FOREIGN KEY (team_id) REFERENCES teams(id) ON DELETE CASCADE
);

-- Tabla del bracket (árbol de llaves)
CREATE TABLE bracket_slots (
    id INT AUTO_INCREMENT PRIMARY KEY,
    round VARCHAR(50) NOT NULL,
    slot_number INT NOT NULL,
    group_name ENUM('A','B','C','D'),
    team_id INT,
    FOREIGN KEY (team_id) REFERENCES teams(id) ON DELETE SET NULL
);

CREATE TABLE bracket_progress (
    id INT AUTO_INCREMENT PRIMARY KEY,
    team_id INT NOT NULL,
    slot_key VARCHAR(50) NOT NULL,
    moved_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (team_id) REFERENCES teams(id) ON DELETE CASCADE
);

-- Tabla de clips
CREATE TABLE clips (
    id INT AUTO_INCREMENT PRIMARY KEY,
    player_name VARCHAR(100) NOT NULL,
    character_icon VARCHAR(255),
    file_path VARCHAR(255) NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de reacciones a clips
CREATE TABLE clip_reactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    clip_id INT NOT NULL,
    emoji VARCHAR(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (clip_id) REFERENCES clips(id) ON DELETE CASCADE
);

CREATE TABLE clip_reaction_order (
    clip_id INT NOT NULL,
    emoji VARCHAR(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
    first_seen TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (clip_id, emoji),
    FOREIGN KEY (clip_id) REFERENCES clips(id) ON DELETE CASCADE
);

-- Tabla de comentarios de clips
CREATE TABLE clip_comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    clip_id INT NOT NULL,
    commenter_name VARCHAR(100) NOT NULL,
    commenter_icon VARCHAR(255),
    comment_text TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (clip_id) REFERENCES clips(id) ON DELETE CASCADE
);

-- Insertar los 3 admins iniciales
-- Contraseñas hasheada
INSERT INTO admins (username, password_hash) VALUES
('h3ar', '$2y$10$hPYtYp9gHk2I07JqCiAVoOlpBEpgGXwJd.aQBxrywd8hMH.yBBEm2'),
('pakorro', '$2y$10$dSE.dFfGTejNqBGpWwEI3ekg2Rr65KFu4GAoq3dEkA6OImG6..dgy'),
('duckman', '$2y$10$Z2Pg81/L71P4LjKg84u8tehhHEbljg3YtmGpyJs4PbfjORkRBkSbO');

-- Admin de prueba, admin - admin123
INSERT INTO admins (username, password_hash) VALUES
('admin', '$2y$10$LQynlM1iYv284RYmSMgz..oAknzbTcnzc2sc/oHB8ytpPDDrSbL9G');

-- Insertar todos los slots del bracket
INSERT INTO bracket_slots (round, slot_number, group_name, team_id) VALUES
-- Grupo A (partidos internos)
('group_A', 1, 'A', NULL),
('group_A', 2, 'A', NULL),
('group_A', 3, 'A', NULL),
('group_A', 4, 'A', NULL),
-- Ganadores internos Grupo A
('group_A_winner', 1, 'A', NULL),
('group_A_winner', 2, 'A', NULL),
-- Ganador Grupo A (pasa a semi)
('winner_A', 1, 'A', NULL),

-- Grupo B (partidos internos)
('group_B', 1, 'B', NULL),
('group_B', 2, 'B', NULL),
('group_B', 3, 'B', NULL),
('group_B', 4, 'B', NULL),
-- Ganadores internos Grupo B
('group_B_winner', 1, 'B', NULL),
('group_B_winner', 2, 'B', NULL),
-- Ganador Grupo B (pasa a semi)
('winner_B', 1, 'B', NULL),

-- Grupo C (partidos internos)
('group_C', 1, 'C', NULL),
('group_C', 2, 'C', NULL),
('group_C', 3, 'C', NULL),
('group_C', 4, 'C', NULL),
-- Ganadores internos Grupo C
('group_C_winner', 1, 'C', NULL),
('group_C_winner', 2, 'C', NULL),
-- Ganador Grupo C (pasa a semi)
('winner_C', 1, 'C', NULL),

-- Grupo D (partidos internos)
('group_D', 1, 'D', NULL),
('group_D', 2, 'D', NULL),
('group_D', 3, 'D', NULL),
('group_D', 4, 'D', NULL),
-- Ganadores internos Grupo D
('group_D_winner', 1, 'D', NULL),
('group_D_winner', 2, 'D', NULL),
-- Ganador Grupo D (pasa a semi)
('winner_D', 1, 'D', NULL),

-- Semifinales
('semi_left',  1, NULL, NULL),
('semi_right', 1, NULL, NULL),

-- Final y 3er lugar
('final',  1, NULL, NULL),
('third',  1, NULL, NULL);