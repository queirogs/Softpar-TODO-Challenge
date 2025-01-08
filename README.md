# Projeto Full-Stack: Sistema TODO com Backend em Laravel, Frontend em Quasar e Banco de Dados PostgreSQL

## Descrição
Este projeto é uma aplicação full-stack que combina um backend robusto desenvolvido com Laravel, um frontend dinâmico criado com Quasar Framework e um banco de dados PostgreSQL para armazenamento de informações. Trata-se de um sistema TODO onde é possível adicionar, editar e excluir tasks e subtasks. O projeto foi desenvolvido como parte de um processo seletivo para a empresa Softpar, visando demonstrar habilidades técnicas e boas práticas de desenvolvimento.

## Configuração do Ambiente

### Requisitos de Software
Certifique-se de ter as seguintes ferramentas instaladas no ambiente:
- PHP >= 8.1
- Composer >= 2.0
- Node.js >= 16.x
- NPM >= 8.x
- PostgreSQL >= 13
- Laravel >= 10
- Quasar Framework >= 2.11

### Configuração do Backend (Laravel)

1. Clone o repositório do projeto:
   ```bash
   git clone https://github.com/queirogs/Softpar-TODO-Challenge.git
   ```

2. Navegue até a pasta do backend:
   ```bash
   cd softpar-backend
   ```

3. Instale as dependências do Laravel:
   ```bash
   composer install
   ```

4. Configure o arquivo `.env`:
   - Copie o arquivo de exemplo:
     ```bash
     cp .env.example .env
     ```
   - Atualize as variáveis de ambiente relacionadas ao banco de dados:
     ```env
     DB_CONNECTION=pgsql
     DB_HOST=127.0.0.1
     DB_PORT=5432
     DB_DATABASE=softpar-db
     DB_USERNAME=postgres
     DB_PASSWORD=4638
     ```

5. Gere a chave da aplicação Laravel:
   ```bash
   php artisan key:generate
   ```

6. Execute as migrações para criar as tabelas no banco de dados:
   ```bash
   php artisan migrate
   ```

7. (Opcional) Popule o banco de dados com dados fictícios:
   ```bash
   php artisan db:seed
   ```

8. Inicie o servidor do backend:
   ```bash
   php artisan serve
   ```
   O backend ficará acessível em: [http://localhost:8000](http://localhost:8000)

### Configuração do Frontend (Quasar)

1. Navegue até a pasta do frontend:
   ```bash
   cd frontend
   ```

2. Instale as dependências do projeto:
   ```bash
   npm install
   ```

3. Inicie o servidor de desenvolvimento:
   ```bash
   quasar dev
   ```
   O frontend ficará acessível em: [http://localhost:9000](http://localhost:9000)

### Configuração do Banco de Dados

1. Certifique-se de que o PostgreSQL esteja instalado e em execução.

2. Crie o banco de dados especificado no arquivo `.env` do backend:
   ```sql
   CREATE DATABASE nome_do_banco;
   ```

3. Verifique se as credenciais no arquivo `.env` correspondem ao banco de dados criado.

4. Utilize as migrações e seeders do Laravel para configurar e popular o banco:
   ```bash
   php artisan migrate --seed
   ```

## Funcionalidades
- **Adicionar Tasks e Subtasks:**
  - No frontend, há opções intuitivas para adicionar novas tarefas e subtarefas.
  - A API no backend gerencia a criação e armazenamento desses itens no banco de dados.

- **Editar Tasks e Subtasks:**
  - É possível modificar o título, descrição ou status de qualquer tarefa ou subtarefa.

- **Excluir Tasks e Subtasks:**
  - Tanto tasks quanto subtasks podem ser excluídas individualmente.

## Funcionalidades Extras
Seguem as funcionalidades e tecnologias extras:

- **Tailwind:**
  - Projeto foi implementado utilizando um framework adicional para CSS: Tailwind.

- **Subtarefas:**
  - Implementação da funcionalidade extra de adicionar subtarefas às tarefas existentes.

## Testando a Aplicação
- Backend: Utilize ferramentas como Postman ou Insomnia para testar as rotas da API.
- Frontend: Acesse o servidor de desenvolvimento e navegue pela interface.
- Banco de Dados: Valide os dados diretamente no PostgreSQL usando ferramentas como pgAdmin ou linha de comando.

Se surgir qualquer dúvida, consulte a documentação do Laravel, Quasar Framework ou PostgreSQL, ou entre em contato comigo pelo e-mail: victorqueirogacarvalho@gmail.com.

