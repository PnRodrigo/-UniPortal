-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 20-Mar-2026 às 02:26
-- Versão do servidor: 10.4.32-MariaDB
-- versão do PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `gestao_ped`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `cursos`
--

CREATE TABLE `cursos` (
  `id` int(11) NOT NULL,
  `nome_c` varchar(100) NOT NULL,
  `ativo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `cursos`
--

INSERT INTO `cursos` (`id`, `nome_c`, `ativo`) VALUES
(1, 'Engenharia de Software', 1),
(2, 'Design e Multimédia', 1),
(3, 'Gestão de Empresas', 1),
(4, 'Psicologia Clínica', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `disciplinas`
--

CREATE TABLE `disciplinas` (
  `id` int(11) NOT NULL,
  `nome_d` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `disciplinas`
--

INSERT INTO `disciplinas` (`id`, `nome_d`) VALUES
(1, 'Programação Orientada a Objetos'),
(2, 'Bases de Dados I'),
(3, 'Arquitetura de Computadores'),
(4, 'Interface Pessoa-Máquina'),
(5, 'Teoria do Design'),
(6, 'Anatomia Humana'),
(7, 'Contabilidade Financeira');

-- --------------------------------------------------------

--
-- Estrutura da tabela `fichas_aluno`
--

CREATE TABLE `fichas_aluno` (
  `id` int(11) NOT NULL,
  `user_login` varchar(50) DEFAULT NULL,
  `nome_completo` varchar(255) DEFAULT NULL,
  `curso_id` int(11) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `estado` enum('Rascunho','Submetida','Aprovada','Rejeitada') DEFAULT 'Rascunho',
  `observacoes` text DEFAULT NULL,
  `validado_por` varchar(50) DEFAULT NULL,
  `data_submissao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `fichas_aluno`
--

INSERT INTO `fichas_aluno` (`id`, `user_login`, `nome_completo`, `curso_id`, `foto`, `estado`, `observacoes`, `validado_por`, `data_submissao`) VALUES
(1, 'aluno', 'Carlos Alberto Rodrigues', 1, 'uploads/foto_aluno_1773945240.png', 'Aprovada', '', 'admin', '2026-03-19 18:23:45'),
(2, 'aluno2', 'Beatriz Maria Santos', 2, 'uploads/foto_aluno2_1773945311.png', 'Submetida', NULL, NULL, '2026-03-19 18:23:45'),
(3, 'aluno3', 'Ricardo Jorge Pereira', 1, 'uploads/foto_aluno3_1773945299.png', 'Submetida', NULL, NULL, '2026-03-19 18:23:45'),
(4, 'aluno4', 'Ana Sofia Matos', 3, 'uploads/foto_aluno4_1773945330.png', 'Submetida', NULL, NULL, '2026-03-19 18:23:45');

-- --------------------------------------------------------

--
-- Estrutura da tabela `matriculas`
--

CREATE TABLE `matriculas` (
  `id` int(11) NOT NULL,
  `aluno_login` varchar(50) DEFAULT NULL,
  `curso_id` int(11) DEFAULT NULL,
  `estado` enum('Pendente','Aprovado','Rejeitado') DEFAULT 'Pendente',
  `observacoes` text DEFAULT NULL,
  `responsavel` varchar(50) DEFAULT NULL,
  `data_pedido` datetime DEFAULT current_timestamp(),
  `data_decisao` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `matriculas`
--

INSERT INTO `matriculas` (`id`, `aluno_login`, `curso_id`, `estado`, `observacoes`, `responsavel`, `data_pedido`, `data_decisao`) VALUES
(1, 'aluno', 1, 'Aprovado', NULL, NULL, '2026-03-19 18:23:45', NULL),
(2, 'aluno3', 1, 'Aprovado', NULL, NULL, '2026-03-19 18:23:45', NULL),
(3, 'aluno2', 2, 'Pendente', NULL, NULL, '2026-03-19 18:23:45', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `pautas`
--

CREATE TABLE `pautas` (
  `id` int(11) NOT NULL,
  `uc_id` int(11) DEFAULT NULL,
  `aluno_login` varchar(50) DEFAULT NULL,
  `nota` decimal(5,2) DEFAULT NULL,
  `epoca` enum('Normal','Recurso','Especial') DEFAULT 'Normal',
  `ano_letivo` varchar(20) DEFAULT NULL,
  `responsavel` varchar(50) DEFAULT NULL,
  `data_registo` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `pautas`
--

INSERT INTO `pautas` (`id`, `uc_id`, `aluno_login`, `nota`, `epoca`, `ano_letivo`, `responsavel`, `data_registo`) VALUES
(1, 1, 'aluno', 17.50, 'Normal', '2023/2024', 'admin', '2026-03-19 18:23:45'),
(2, 2, 'aluno', 14.00, 'Normal', '2023/2024', 'admin', '2026-03-19 18:23:45'),
(3, 1, 'aluno3', 12.00, 'Normal', '2023/2024', 'admin', '2026-03-19 18:23:45'),
(4, 2, 'aluno3', 9.00, 'Normal', '2023/2024', 'admin', '2026-03-19 18:23:45'),
(5, 2, 'aluno3', 13.50, 'Recurso', '2023/2024', 'admin', '2026-03-19 18:23:45'),
(6, 3, 'aluno', 7.77, 'Normal', '2026/2027', 'funcionario', '2026-03-19 18:24:19');

-- --------------------------------------------------------

--
-- Estrutura da tabela `plano_estudos`
--

CREATE TABLE `plano_estudos` (
  `id` int(11) NOT NULL,
  `curso_id` int(11) DEFAULT NULL,
  `disciplina_id` int(11) DEFAULT NULL,
  `ano` int(11) DEFAULT NULL,
  `semestre` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `plano_estudos`
--

INSERT INTO `plano_estudos` (`id`, `curso_id`, `disciplina_id`, `ano`, `semestre`) VALUES
(1, 1, 1, 1, 1),
(2, 1, 2, 1, 2),
(3, 1, 3, 2, 1),
(4, 1, 4, 2, 2),
(5, 2, 5, 1, 1),
(6, 3, 7, 1, 1),
(7, 4, 6, 1, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

CREATE TABLE `users` (
  `login` varchar(50) NOT NULL,
  `pwd` varchar(255) NOT NULL,
  `grupo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `users`
--

INSERT INTO `users` (`login`, `pwd`, `grupo`) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1),
('aluno', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2),
('aluno2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2),
('aluno3', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2),
('aluno4', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2),
('funcionario', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 3);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `disciplinas`
--
ALTER TABLE `disciplinas`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `fichas_aluno`
--
ALTER TABLE `fichas_aluno`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_login` (`user_login`),
  ADD KEY `curso_id` (`curso_id`);

--
-- Índices para tabela `matriculas`
--
ALTER TABLE `matriculas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `aluno_login` (`aluno_login`),
  ADD KEY `curso_id` (`curso_id`);

--
-- Índices para tabela `pautas`
--
ALTER TABLE `pautas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uc_id` (`uc_id`),
  ADD KEY `aluno_login` (`aluno_login`);

--
-- Índices para tabela `plano_estudos`
--
ALTER TABLE `plano_estudos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `curso_id` (`curso_id`,`disciplina_id`,`ano`,`semestre`),
  ADD KEY `disciplina_id` (`disciplina_id`);

--
-- Índices para tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`login`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `cursos`
--
ALTER TABLE `cursos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `disciplinas`
--
ALTER TABLE `disciplinas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `fichas_aluno`
--
ALTER TABLE `fichas_aluno`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `matriculas`
--
ALTER TABLE `matriculas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `pautas`
--
ALTER TABLE `pautas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `plano_estudos`
--
ALTER TABLE `plano_estudos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `fichas_aluno`
--
ALTER TABLE `fichas_aluno`
  ADD CONSTRAINT `fichas_aluno_ibfk_1` FOREIGN KEY (`user_login`) REFERENCES `users` (`login`),
  ADD CONSTRAINT `fichas_aluno_ibfk_2` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`);

--
-- Limitadores para a tabela `matriculas`
--
ALTER TABLE `matriculas`
  ADD CONSTRAINT `matriculas_ibfk_1` FOREIGN KEY (`aluno_login`) REFERENCES `users` (`login`),
  ADD CONSTRAINT `matriculas_ibfk_2` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`);

--
-- Limitadores para a tabela `pautas`
--
ALTER TABLE `pautas`
  ADD CONSTRAINT `pautas_ibfk_1` FOREIGN KEY (`uc_id`) REFERENCES `disciplinas` (`id`),
  ADD CONSTRAINT `pautas_ibfk_2` FOREIGN KEY (`aluno_login`) REFERENCES `users` (`login`);

--
-- Limitadores para a tabela `plano_estudos`
--
ALTER TABLE `plano_estudos`
  ADD CONSTRAINT `plano_estudos_ibfk_1` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`),
  ADD CONSTRAINT `plano_estudos_ibfk_2` FOREIGN KEY (`disciplina_id`) REFERENCES `disciplinas` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
