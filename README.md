**Desenvolvedor Back End Jr - Desafio**

---

### 1. Introdução

Essa API foi desenvolvida para um sistema de gerenciamento de pedidos, clientes e produtos por meio de endpoints RESTful, permitindo operações CRUD (Create, Read, Update, Delete). O retorno dos dados segue um formato estruturado com parâmetros, mensagem e retorno, conforme exigido no desafio. Além disso, os endpoints contam com filtragem de dados no método GET.

---

### 2. Recursos e Funcionalidades

- Conta com diversos recursos que garantem a segurança dos dados.
- A autenticação JWT faz com que apenas os usuários autenticados possam acessar os endpoints protegidos.
- Garante que apenas dados válidos sejam armazenados no banco.
- A tabela de pedidos está diretamente relacionada às tabelas clientes e produtos.

---

### 3. Tecnologias Utilizadas

- PHP 8.0.30 ou superior
- Banco de dados MySQL
- CodeIgniter 4.4.8
- Composer (Para a biblioteca firebase/php-jwt)
- Extensões necessárias para o php.ini (intl, mysqli, mbstring)
- Visual Studio Code

---

### 4. Configuração do Banco de Dados

#### 4.1 Migrations

As tabelas foram criadas utilizando Migrations. Ao executar o comando no terminal do projeto `php spark migrate`, as tabelas são criadas dentro do banco de dados.

#### 4.2 Autenticação JWT

Para a autenticação JWT, foi criada uma tabela contendo os seguintes campos: `id`, `nome`, `email`, `senha` e `token`. O token não é enviado ao banco quando cadastrado, é gerado apenas no login e depois salvo no banco de dados.

---

### 5. Como executar o Projeto

#### 5.1 Instalação do ambiente

- Caso não tenha o PHP instalado, pode instalar da internet e seguir as instruções de instalação.
- Caso contrário, baixe as pastas dentro de `projeto`, `php` e `desafio`.
- Extraia a pasta `php` para o diretório `C:\`.
- Extraia a pasta `desafio` para um lugar de sua preferência.

#### 5.2 Configuração do banco de dados

- Conecte-se localmente no Workbench.
- Crie um schema chamado `desafio`.

#### 5.3 Configuração do Ambiente

- Abra a pasta `desafio` no Visual Studio Code.
- No terminal do Visual Studio, digite os seguintes comandos:
- $env:PATH += ";C:\\php"
- php spark migrate
- php -S localhost:8000 -t public
- O primeiro adiciona temporariamente o caminho do PHP para o ambiente do projeto, o segundo executa as migrations e cria as tabelas no banco de dados, o terceiro inicia o servidor na porta 8000.

#### 5.4 Inserção dos Dados no Workbench

1. Acesse a pasta do projeto e encontre o arquivo `dadosInserir.sql`.
2. Abra o arquivo e copie todo o conteúdo.
3. Conecte-se ao seu banco de dados local.
4. Crie uma nova aba de query (`File > New Query Tab`).
5. Cole o código copiado na aba de query.
6. Clique no ícone "Executar".

#### 5.5 Possíveis Erros

- Se houver erro de conexão com o banco de dados, verifique no arquivo `.env`, a porta do banco de dados pode estar diferente.
- Caso não tenha conseguido criar uma conexão localmente, instale o XAMPP e inicie o MySQL.

---

### 6. Como acessar as APIs

Esse projeto utiliza o Insomnia, mas pode ser testado em qualquer programa de sua preferência.

#### 6.1 Cadastro

- Endpoint: `POST /registrar`
- URL: `http://localhost:8000/registrar`
- Envie os dados conforme exigido no campo de resposta da API.

#### 6.2 Login

- Endpoint: `POST /login`
- Após o login, a API retorna um token de acesso.

#### 6.3 Utilizando o token

- No Insomnia, vá à aba Headers.
- Adicione um novo header com:
  - **Key**: `Authorization`
  - **Value**: `Bearer seu_token`

#### 6.4 Validando o token

- Endpoint: `GET /user`
- Se o token JWT for válido, retorna os dados do usuário.

---

### 7. Estrutura da URL

A API possui três endpoints protegidos para quem possui um token JWT válido:

- **Pedidos** (`/pedidos`)
- **Produtos** (`/produtos`)
- **Clientes** (`/clientes`)

Cada endpoint possui operações CRUD.

---

### 8. Métodos Disponíveis

#### 8.1 GET

- Listar todos os IDs: `/pedidos`
- Listar 10 IDs da posição 11 a 20: `/pedidos?limit=10&page=2`
- Buscar apenas 1 ID: `/pedidos/2`

#### 8.2 POST

- Enviar os dados obrigatórios.

#### 8.3 PUT

- Necessário fornecer um ID válido.

#### 8.4 DELETE

- Necessário fornecer um ID válido.

---

### 9. Tratamento de Erros

- Se os dados enviados forem inválidos, a API retorna uma mensagem explicativa.
- Se o token JWT estiver inativo, a requisição resultará em erro.

---

### 10. Arquitetura da API

- Utiliza Middlewares para autenticação JWT.
- Migrations para versionamento do banco.
- A estrutura do projeto segue o padrão MVC, porém, devido à limitação de tempo, a lógica CRUD foi implementada diretamente nos Controllers em vez de ser separada nos Models
---

### 11. Exemplo de Resposta da API
```
{
  "parametro": {
    "metodo": "GET",
    "rota": "clientes/"
  },
  "cabecalho": {
    "status": 200,
    "mensagem": {
      "Página": null,
      "Página por ID": "todos",
      "Total de dados": 0
    }
  },
  "retorno": []
}
```
Essa estrutura é mantida para todas as respostas da API.

