# Laravel Multi-Tenant Package (Laravel 12)

**Pacote modular para arquitetura multi-tenant com bancos de dados isolados, subdomínios, comandos Artisan personalizados e autenticação com Passport.**

---

## ✨ Principais Funcionalidades

- 🔀 Identificação automática de tenant por subdomínio
- 🗄️ Bancos de dados separados por tenant
- 🔧 Migrations e seeders separados para tenants
- 🧩 Comandos Artisan para gerenciar tenants
- 🔐 Autenticação OAuth2 via Laravel Passport
- 🚀 Cache e filas isoladas por tenant *(planejado)*
- ⚙️ Extensível, modular e fácil de integrar

---

## ⚙️ Instalação

> ⚠️ Este pacote assume que você está usando **Laravel 12** e **MySQL** com ambiente Dockerizado opcional.

Instale o pacote manualmente (caso não esteja publicado ainda):

```bash
# Clone ou copie os arquivos dentro de packages/MultiTenant
# Registre o ServiceProvider no config/app.php (ou via package discovery)
```

---

## 🚀 Setup Inicial

2. **Rode o comando para instalar propagar as migrations do Passport para todos os tenants:**

```bash
php artisan tenant:passport-install
```

---

## 🏗️ Comandos Disponíveis

### 🎯 Criação de Tenants

```bash
php artisan tenant:create {name} {subdomain}
```

Cria um novo tenant, seu banco de dados, aplica as migrations normais e do Passport.

### 📤 Remoção de Tenants

```bash
php artisan tenant:delete {id}
```

Remove o tenant e o banco de dados correspondente, com confirmação de segurança.

```bash
php artisan tenant:disable {id}
```

Desativa o tenant (sem deletar dados), útil para bloqueio temporário.

### 🧱 Migrations e Seeders

```bash
php artisan make:tenant-migration {name}
php artisan make:tenant-seeder {name}
php artisan tenant:migrate [--tenant={id}]
php artisan tenant:seed [--tenant={id}]
```

Permitem gerenciar a estrutura e dados de cada tenant individualmente ou em lote.

### 🔐 Autenticação Passport

```bash
php artisan tenant:passport-install
```

Executa o `passport:install` globalmente (apenas 1x) e aplica as migrations do Passport para todos os tenants.

---

## 🧩 Estrutura de Pastas

```
packages/
  MultiTenant/
    src/
      Commands/
      Middleware/
      Models/
      Support/
    routes/
    database/
      migrations/
        tenant/
      seeders/
        tenant/
```

---

## 🌐 Subdomínio e Identificação de Tenant

Um middleware `tenant.identify` é usado para extrair o subdomínio da requisição, identificar o tenant e configurar a conexão com o banco de dados correspondente.

```php
Route::middleware('tenant.identify')->group(function () {
    Route::get('/dashboard', fn () => response()->json(['msg' => 'Olá, tenant!']));
});
```

---

## 🔒 Autenticação OAuth2 (Passport)

A autenticação segue o padrão OAuth2 com Laravel Passport. Após a criação do tenant, ele já estará com as tabelas e estrutura do Passport, pronto para autenticar usuários.

> Os tokens são assinados pelas chaves globais do sistema, mas validados por tenant, garantindo segurança e isolamento.

---

## 📅 Funcionalidades Futuras

- [ ] Cache isolado por tenant (via Redis prefixado)
- [ ] Filas isoladas por tenant (workers separados)
- [ ] Eventos multi-tenant

---

## 🛠️ Requisitos

- PHP 8.2+
- Laravel 12
- MySQL
- Laravel Passport
- Docker (opcional, mas recomendado)

---

## 📬 Contribuindo

Pull Requests e sugestões são bem-vindas! Se quiser contribuir com novas funcionalidades ou melhorias na arquitetura, fique à vontade.

---

## 🧑‍💻 Autor

Desenvolvido por Junior Boschet com ❤️ para aplicações SaaS escaláveis e seguras.