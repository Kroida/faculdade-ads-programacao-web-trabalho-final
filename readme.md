# UniFood 🍔

Projeto PHP desenvolvido para a disciplina de Programação Web. O sistema simula uma lanchonete online com cadastro/login de usuários, edição de perfil, painel administrativo e vitrine dinâmica de produtos em destaque.

## ✨ Funcionalidades principais

- Cadastro e login de usuários com senha protegida por `password_hash()`.
- Sessão de usuário com `$_SESSION`.
- Atualização de nome, e-mail, senha e foto de perfil.
- Exclusão da própria conta usando soft delete.
- Painel administrativo protegido para o usuário com `id = 1`.
- CRUD de usuários no painel administrativo.
- Proteção para impedir editar ou deletar o administrador principal.
- Edição dinâmica das sections de destaque da página inicial.
- Cadastro de novas sections destaque direto pelo `adm.php`.
- Upload de imagens para produtos/destaques.
- Modal de pedido com cálculo dinâmico de preço.
- Banco MySQL rodando via Docker.

## 🧱 Tecnologias usadas

- PHP
- MySQL
- PDO
- HTML5
- CSS3
- JavaScript
- Docker Compose
- phpMyAdmin
- Apache/XAMPP para servir o PHP localmente

## 📁 Estrutura do projeto

```text
trabaio_pweb/
├─ adm.php                    # Painel administrativo
├─ index.php                  # Página inicial com sections dinâmicas
├─ login.php                  # Tela de login/cadastro
├─ dadosCliente.php           # Perfil do usuário logado
├─ docker-compose.yml         # MySQL e phpMyAdmin via Docker
├─ auth/
│  ├─ auth.php                # Proteção de páginas logadas
│  ├─ authAdm.php             # Proteção de páginas admin
│  ├─ authPedido.php          # Validação de pedido
│  ├─ logout.php              # Logout
│  ├─ processaDelete.php      # Exclusão da própria conta
│  ├─ processaLogin.php       # Login
│  ├─ processaSignup.php      # Cadastro
│  └─ processaUpdate.php      # Atualização de perfil
├─ config/
│  ├─ admFunctions.php        # Funções de CRUD e destaques
│  └─ conexao.php             # Conexão PDO
├─ database/
│  └─ desgraca.sql            # Script do banco
├─ includes/
│  ├─ header.php              # Cabeçalho padrão
│  └─ footer.php              # Rodapé e scripts
├─ src/
│  ├─ js/
│  │  ├─ index.js             # Modal de pedido e pagamento
│  │  └─ login.js             # Login/cadastro/foto/modal de exclusão
│  ├─ styles/
│  │  └─ style.css            # Estilos do projeto
│  └─ img/                    # Imagens estáticas
└─ uploads/
   ├─ default/                # Imagens padrão
   ├─ destaques/              # Uploads das sections destaque
   └─ profiles/               # Uploads de foto de perfil
```

## 🚀 Como rodar o projeto

### 1. Subir o banco com Docker

Na pasta raiz do projeto, execute:

```bash
docker compose up -d
```

Isso sobe:

- MySQL na porta `3307` do computador.
- phpMyAdmin em `http://localhost:8080`.

### 2. Importar o banco

O arquivo `database/desgraca.sql` é usado como script inicial do container. Se precisar resetar o banco do zero:

```bash
docker compose down -v
docker compose up -d
```

⚠️ O comando `down -v` apaga o volume do banco.

### 3. Rodar o PHP pelo XAMPP

Coloque a pasta do projeto em:

```text
C:\xampp\htdocs\trabaio_pweb
```

Inicie o Apache no XAMPP e acesse:

```text
http://localhost/trabaio_pweb
```

## 🔐 Acesso padrão

Administrador principal:

```text
E-mail: goku@gmail.com
Senha: 4321
```

O administrador principal possui `id = 1` e não pode ser editado nem deletado pelo painel.

## 🗄️ Banco de dados

O projeto usa duas tabelas principais:

### `users`

Armazena usuários do sistema.

Campos importantes:

- `id`
- `name`
- `email`
- `password_hash`
- `profile_image`
- `status`
- `created_at`
- `updated_at`
- `deleted_at`

### `featured_sections`

Armazena os produtos/destaques exibidos dinamicamente na página inicial.

Campos importantes:

- `html_id`: ID HTML da section.
- `product_key`: chave usada pelo botão de pedido.
- `badge_text`: texto da etiqueta do produto.
- `title`: título do destaque.
- `description`: descrição.
- `price`: preço principal.
- `combo_price`: preço opcional de combo.
- `image_path`: caminho da imagem.
- `image_alt`: texto alternativo da imagem.
- `is_light`: define fundo claro.
- `is_reverse`: inverte posição de texto/imagem.
- `is_active`: mostra ou esconde na home.
- `display_order`: ordem de exibição.

## 🧠 Como funciona a vitrine dinâmica

Antes, as sections da home ficavam escritas diretamente no `index.php`. Agora o fluxo é:

```text
adm.php
  ↓ salva/edita
featured_sections
  ↓ consulta
index.php
  ↓ renderiza
<section class="section-destaque">
```

O `index.php` busca os destaques ativos com:

```php
$destaques = listDestaques($pdo, true);
```

Depois renderiza cada item com `foreach`. O botão `Adicionar ao Pedido` recebe dados via atributos `data-*`, e o `src/js/index.js` usa esses dados para preencher o modal.

## 🛡️ Segurança aplicada

- Uso de PDO com prepared statements.
- Senhas salvas com `password_hash()`.
- Verificação de senha com `password_verify()`.
- `htmlspecialchars()` para reduzir risco de XSS na saída HTML.
- Validação de upload por MIME type.
- Bloqueio de edição/deleção do usuário administrador ID 1.
- Conexão com `charset=utf8mb4` para evitar problemas com acentos.

## 📝 Observações de manutenção

- O MySQL do projeto roda via Docker, não pelo XAMPP.
- O Apache do XAMPP serve os arquivos PHP.
- A conexão fica em `config/conexao.php`.
- Se CSS parecer antigo no navegador, use `Ctrl + F5` para forçar atualização.
- Novas imagens de destaque ficam em `uploads/destaques/`.
- Fotos de perfil ficam em `uploads/profiles/`.

## 🧩 Arquivos mais importantes para estudar

- `config/admFunctions.php`: regras de CRUD, validações e funções reutilizáveis.
- `adm.php`: painel administrativo e formulários de ação.
- `index.php`: renderização dinâmica da home.
- `src/js/index.js`: lógica do modal de pedido.
- `src/js/login.js`: prévia de foto, alternância de login/cadastro e modal de exclusão.
