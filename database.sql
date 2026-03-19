-- BASE DE DADOS: GESTAO_PED
-- (MOCKUP)

CREATE DATABASE IF NOT EXISTS gestao_ped;
USE gestao_ped;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS pautas;
DROP TABLE IF EXISTS matriculas;
DROP TABLE IF EXISTS fichas_aluno;
DROP TABLE IF EXISTS plano_estudos;
DROP TABLE IF EXISTS disciplinas;
DROP TABLE IF EXISTS cursos;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS = 1;

-- 1. DEFINIÇÃO DAS TABELAS

CREATE TABLE users (
    login VARCHAR(50) PRIMARY KEY,
    pwd VARCHAR(255) NOT NULL,
    grupo INT NOT NULL -- 1: Gestor, 2: Aluno, 3: Staff
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE cursos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_c VARCHAR(100) NOT NULL,
    ativo BOOLEAN DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE disciplinas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_d VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE plano_estudos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    curso_id INT,
    disciplina_id INT,
    ano INT,
    semestre INT,
    FOREIGN KEY (curso_id) REFERENCES cursos(id),
    FOREIGN KEY (disciplina_id) REFERENCES disciplinas(id),
    UNIQUE KEY (curso_id, disciplina_id, ano, semestre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE fichas_aluno (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_login VARCHAR(50),
    nome_completo VARCHAR(255),
    curso_id INT,
    foto VARCHAR(255),
    estado ENUM('Rascunho', 'Submetida', 'Aprovada', 'Rejeitada') DEFAULT 'Rascunho',
    observacoes TEXT,
    validado_por VARCHAR(50),
    data_submissao DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_login) REFERENCES users(login),
    FOREIGN KEY (curso_id) REFERENCES cursos(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE matriculas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    aluno_login VARCHAR(50),
    curso_id INT,
    estado ENUM('Pendente', 'Aprovado', 'Rejeitado') DEFAULT 'Pendente',
    observacoes TEXT,
    responsavel VARCHAR(50),
    data_pedido DATETIME DEFAULT CURRENT_TIMESTAMP,
    data_decisao DATETIME,
    FOREIGN KEY (aluno_login) REFERENCES users(login),
    FOREIGN KEY (curso_id) REFERENCES cursos(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE pautas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    uc_id INT,
    aluno_login VARCHAR(50),
    nota DECIMAL(5,2),
    epoca ENUM('Normal', 'Recurso', 'Especial') DEFAULT 'Normal',
    ano_letivo VARCHAR(20),
    responsavel VARCHAR(50),
    data_registo DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (uc_id) REFERENCES disciplinas(id),
    FOREIGN KEY (aluno_login) REFERENCES users(login)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. DADOS DE TESTE COMPLETOS (MOCKUP DATA)
-- Hash da senha 'password'

INSERT INTO users (login, pwd, grupo) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1),
('aluno', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2),
('aluno2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2),
('aluno3', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2),
('aluno4', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2),
('funcionario', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 3);

INSERT INTO cursos (nome_c) VALUES 
('Engenharia de Software'),
('Design e Multimédia'),
('Gestão de Empresas'),
('Psicologia Clínica');

INSERT INTO disciplinas (nome_d) VALUES 
('Programação Orientada a Objetos'),
('Bases de Dados I'),
('Arquitetura de Computadores'),
('Interface Pessoa-Máquina'),
('Teoria do Design'),
('Anatomia Humana'),
('Contabilidade Financeira');

INSERT INTO plano_estudos (curso_id, disciplina_id, ano, semestre) VALUES 
(1, 1, 1, 1), 
(1, 2, 1, 2), 
(1, 3, 2, 1),
(1, 4, 2, 2),
(2, 5, 1, 1),
(3, 7, 1, 1),
(4, 6, 1, 1);

INSERT INTO fichas_aluno (user_login, nome_completo, curso_id, estado, foto) VALUES 
('aluno', 'Carlos Alberto Rodrigues', 1, 'Rascunho', 'uploads/placeholder.svg'),
('aluno2', 'Beatriz Maria Santos', 2, 'Rascunho', 'uploads/placeholder.svg'),
('aluno3', 'Ricardo Jorge Pereira', 1, 'Rascunho', 'uploads/placeholder.svg'),
('aluno4', 'Ana Sofia Matos', 3, 'Rascunho', 'uploads/placeholder.svg');

INSERT INTO matriculas (aluno_login, curso_id, estado) VALUES 
('aluno', 1, 'Aprovado'),
('aluno3', 1, 'Aprovado'),
('aluno2', 2, 'Pendente');

INSERT INTO pautas (uc_id, aluno_login, nota, epoca, ano_letivo, responsavel) VALUES 
(1, 'aluno', 17.5, 'Normal', '2023/2024', 'admin'),
(2, 'aluno', 14.0, 'Normal', '2023/2024', 'admin'),
(1, 'aluno3', 12.0, 'Normal', '2023/2024', 'admin'),
(2, 'aluno3', 9.0, 'Normal', '2023/2024', 'admin'),
(2, 'aluno3', 13.5, 'Recurso', '2023/2024', 'admin');
