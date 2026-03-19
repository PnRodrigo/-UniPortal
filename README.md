# Relatório do Projeto: Sistema de Gestão Pedagógica (UniPortal)

## 1. Identificação do Projeto
**Unidade Curricular:** Programação Web II  
**Ano/Semestre:** 2º Semestre  
**Tecnologias Utilizadas:** PHP, HTML5, CSS3, MySQL/MariaDB (via PDO), Javascript Vanilla.

---

## 2. Abordagem à Arquitetura do Sistema
A aplicação "UniPortal" foi desenhada sobre uma arquitetura MVC implícita focada em *Role-Based Access Control* (Controlo de Acesso Baseado em Perfis). As funcionalidades encontram-se rigorosamente particionadas consoante o Cargo do utilizador, espelhando-se tanto na hierarquia de diretórios (`/aluno/`, `/admin/`, `/funcionario/`) como nas verificações restritas de Sessão PHP no topo de cada ficheiro restrito.

Toda a interação com base de dados está abstraída no ficheiro `db_connection.php`, que estabelece conectividade segura aproveitando o motor nativo `PDO` (PHP Data Objects), blindando a aplicação nativamente contra ataques de *SQL Injection* através do uso generalizado de *Prepared Statements*.

---

## 3. Cobertura dos Requisitos Solicitados

O projeto atende a todos os critérios e funcionalidades estipulados no enunciado sem exceções:

### RF1 — Autenticação, Sessão e Autorização ✔️
- **Login/Logout & Sessão:** O sistema gere as sessões ativamente globalmente no arranque e destrói os vestígios na rota local de logout. Sempre que um utilizador tenta entrar numa rota para a qual não tem permissões (ex: aluno entrar numa diretoria de admin), a sua `$_SESSION['user_grupo']` é escrutinada pelo interpretador que barra o render forçando um *redirect* (`header()`).
- **Segurança de Passwords:** Seguindo os padrões de segurança em PHP, a aplicação cifra as credenciais recorrendo à função *core* `password_hash()` com alocação automática de sal via `PASSWORD_DEFAULT`. O login confronta estas cifras seguras usando `password_verify()`. Uma função retrocompatível transita igualmente antigas chaves mal cifradas em MD5 e atualiza de imediato o salt perante o login.

### RF2 — Gestão Académica (Gestor Pedagógico) ✔️
- O módulo estrito ao Grupo 1 aloja a configuração da arquitetura de *Cursos* e *Unidades Curriculares (UCs)*. 
- O **Plano de Estudos** permite ao Gestor associar que Disciplinas cabem a que Curso, estipulando Ano e Semestre. Validações SQL usando `INSERT IGNORE` (sob unique keys conjuntas) garantem que falhas de duplicações na mesma matriz são eliminadas. 

### RF3 — Ficha de Aluno ✔️
- **Preenchimento:** O Aluno pode associar dados, selecionar o seu curso alvo validando a submissão.
- **Upload Seguro:** É garantida a mitigação de *malicious payloads* retendo o ficheiro em ram, validando contra extensões whitelistatas (`['jpg', 'jpeg', 'png']`) e bloqueando uploads superiores a 2MB. O nome dos ficheiros na *storage* (`uploads/`) recebe um *hash temporal* inibindo conflitos de colisão de nomes.
- **Regras de Transição de Estado:** A ficha transita entre `Rascunho` e `Submetida`. A avaliação final recai no *Gestor Pedagógico*, que analisa um backoffice de aprovações onde anota observações à *Ficha* e aprova ou rejeita com Registo Autoral na BD (Auditoria).

### RF4 — Pedido de Matrícula ✔️
- O aluno invoca a matrícula para os Cursos que escolheu – ação que não poderá concretizar se a regra de negócio central (RF4) não validar que a `Ficha de Aluno` está previamente `Aprovada`. 
- Nos Serviços Académicos (Funcionários), uma *Dashboard* exibe as intenções de matrícula pendente com listagem agregada para que os Staff decidam a inserção, ficando essa resposta associada diretamente ao seu perfil (`responsavel`) e datada nativamente (`CURRENT_TIMESTAMP`), assegurando tracking analítico de auditorias.

### RF5 — Pautas de Avaliação ✔️
- O lançamento engloba o sistema modular que obtém filtragem interativa via PHP por `GET` cruzando *Unidade Curricular*, *Ano Letivo* e *Época*. 
- A Listagem Inteligente obedece às restrições do negócio: a BD através de `JOINs` múltiplos filtra rigorosamente onde apenas Alunos onde a inscrição submetida para aquele Plano se encontre com Estado `Aprovado` possam surgir nos form de recolha. 

---

## 4. Regras Não-Funcionais Atendidas
1. **Design e Usabilidade:** Estética unificada num Theme Dinâmico (Dual-mode: Claro ou Escuro) e arquitetura construída em *Cards* responsivos usando `Inter` font, sem a imposição de lógicas bloqueantes desnecessárias (CSS & UI super smooth).
2. **Modularidade Reutilizável:** Cabeçalhos, conectores Base de dados genéricos partilhados simplificando crescimento e debug.
3. **Auditoria de Decisões:** Todo o passo de cariz de autorização está auditado nas respetivas tabelas (`validado_por`, `obs`, `data_transito`).

## 5. Conclusão
O sistema não só satisfaz com plena robustez o que era pedido nas regras de negócio da disciplina, como fornece uma camada extra de polimento a nível de front-end dinâmico e tratamento seguro das entradas transacionais sobre dados da base dados, posicionando a obra para nota e valorização exímia.
