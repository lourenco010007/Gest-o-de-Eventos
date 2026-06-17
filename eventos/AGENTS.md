# Guia de Desenvolvimento - Sistema de Gestão de Eventos

> **Objetivo:** Ajudar agentes de IA a compreender arquitetura, convenções e padrões de desenvolvimento deste projeto.

## Stack Tecnológico

- **Backend:** Laravel 12 (PHP 8.2+)
- **Frontend:** Blade templates + Tailwind CSS 4 + Vite 7
- **Database:** MySQL (migrations em `database/migrations/`)
- **Testes:** PHPUnit 11.5

## Setup e Execução

### Primeira execução (setup completo)
```bash
composer run setup
```
Instala dependências PHP/Node, gera `.env`, cria chave de app, roda migrations e builds frontend.

### Desenvolvimento local
```bash
composer run dev
```
Roda em paralelo: Laravel server, queue listener, log watcher, Vite dev server.

### Testes
```bash
composer run test
```
Limpa cache de config e roda suite de testes com PHPUnit.

## Estrutura de Arquivos

```
app/
  ├── Models/
  │   └── Evento.php           # Modelo Eloquent para eventos
  ├── Http/
  │   └── Controllers/
  │       └── EventoController # CRUD de eventos + dashboard
  └── Providers/
routes/
  └── web.php                  # Rotas principais (home, eventos, about, contact)
resources/
  ├── views/
  │   ├── layouts/
  │   ├── pages/              # Páginas principais (home, eventos, about, contact)
  │   └── form/               # Formulários (cadastro de eventos)
  ├── js/
  └── css/
database/
  ├── migrations/             # Schema do banco (eventos_table, users_table, etc)
  └── seeders/
public/
  ├── css/
  ├── js/
  └── img/
```

## Padrões de Desenvolvimento

### 1. Modelos (Eloquent ORM)
- Definir campos em migrations, não em modelos
- Usar timestamps automáticos (`created_at`, `updated_at`)
- Exemplo: [app/Models/Evento.php](app/Models/Evento.php)

### 2. Controllers
- Métodos padrão CRUD: `index()`, `create()`, `store()`, `show()`, `edit()`, `update()`, `destroy()`
- Validar requests com `$request->validate()`
- Usar try-catch para tratamento de erros
- Retornar vistas com `view('path', compact('data'))`
- Exemplo: [app/Http/Controllers/EventoController.php](app/Http/Controllers/EventoController.php)

### 3. Rotas (RESTful)
- Padrão: `Route::get('/recurso', [Controller::class, 'method'])`
- Localizado em [routes/web.php](routes/web.php)
- Endpoints atuais:
  - `GET /` → Home
  - `GET /eventos` → Listar eventos
  - `GET /eventos/create` → Formulário criar evento
  - `POST /eventos` → Salvar evento
  - `GET /dashboard/data` → JSON para dashboard (gráficos)
  - `GET /about` → Sobre
  - `GET /contact` → Contato

### 4. Views Blade
- Use `@extends('layouts.main')` para herança de layout
- `@foreach($collection as $item)` para iteração
- `{{ $variable }}` para echo seguro (escape HTML)
- `{!! $raw_html !!}` apenas se confiável (escape não aplicado)
- Exemplo: [resources/views/pages/eventos.blade.php](resources/views/pages/eventos.blade.php)

### 5. Frontend
- **CSS:** Tailwind CSS 4 (utility-first) + Bootstrap (grid/components)
- **Build:** Vite (hot reload em dev, minificação em prod)
- Entry points: `resources/js/app.js` e `resources/css/app.css`
- Assets compilados para `public/css/` e `public/js/`

### 6. Banco de Dados
- Usar migrations versionadas para schema
- Campos de `Evento`:
  - `id` (PK, auto-increment)
  - `title` (string, max 255)
  - `description` (text)
  - `date` (date)
  - `city` (string, max 255)
  - `salon` (string, max 255)
  - `image` (string, nullable) - para URLs de imagens
  - `private` (boolean)
  - `created_at`, `updated_at` (timestamps)

## Convenções de Código

1. **Namespaces:** PSR-4 - `App\Models`, `App\Http\Controllers`, `Tests\`
2. **Nomenclatura:**
   - Classes: PascalCase (`EventoController`, `Evento`)
   - Métodos/funções: camelCase (`getDashboardData()`)
   - Constantes: UPPER_SNAKE_CASE (`API_KEY`)
   - Views: snake_case (`eventos.blade.php`, `form/cadastro.blade.php`)
3. **Indentação:** 4 espaços (PHP), espaços ou tabs configurável (JS)
4. **PSR-12:** Usar Laravel Pint para formatação automática:
   ```bash
   ./vendor/bin/pint
   ```

## Checklist para Nova Funcionalidade

1. ✅ Criar migration: `php artisan make:migration create_table_name`
2. ✅ Criar model se necessário: `php artisan make:model ModelName`
3. ✅ Criar controller: `php artisan make:controller ControllerName --resource`
4. ✅ Adicionar rotas em `routes/web.php`
5. ✅ Criar/editar views em `resources/views/`
6. ✅ Testar: `composer run test`
7. ✅ Formatear código: `./vendor/bin/pint`

## Comandos Úteis

```bash
# Laravel Artisan
php artisan migrate               # Aplicar todas as migrations pendentes
php artisan migrate:rollback      # Reverter última migration
php artisan tinker                # REPL interativo (debug)
php artisan config:clear          # Limpar cache de config

# Composer/npm
composer install                  # Instalar dependências PHP
npm install                       # Instalar dependências JavaScript
npm run build                     # Build frontend para produção
npm run dev                       # Dev server com hot reload

# Testes
./vendor/bin/phpunit             # Rodar testes sem cache
composer run test                # Rodar testes com cache limpo
```

## Perguntas Frequentes

**P: Onde adicionar lógica de validação complexa?**  
R: Use Form Requests customizadas (`php artisan make:request StorEventoRequest`) em vez de validar direto no controller.

**P: Como adicionar autenticação?**  
R: Laravel inclui scaffolding de auth - use `php artisan breeze:install` ou configure manualmente em `config/auth.php`.

**P: Onde armazenar arquivos uploaded?**  
R: Usar `Storage` facade. Campo `image` em `Evento` pode armazenar path relativo. Exemplo: `$request->file('image')->store('eventos', 'public')`.

**P: Como debuggar?**  
R: Usar `dd()` para dump-and-die, `Log::info()` para logs, ou PHPStorm debugger com Xdebug.

---

**Última atualização:** 2026-06-16  
**Versão:** Laravel 12.x | PHP 8.2+
