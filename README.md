# WP Smart 404 Redirect

Plugin WordPress que intercepta erros 404 e redireciona automaticamente para URLs corretas baseadas no slug do post.

## 📋 Descrição

Este plugin foi desenvolvido para resolver problemas de erro 404 causados por mudanças na estrutura de URLs de posts no WordPress. Quando um usuário acessa uma URL antiga que resulta em erro 404, o plugin:

1. Extrai o slug da URL requisitada
2. Busca no banco de dados por um post com esse slug
3. Redireciona automaticamente (301 - permanente) para a URL correta baseada na **estrutura de permalinks configurada** no WordPress

**✨ Funcionalidades:**
- ✅ Respeita automaticamente as configurações de permalinks do WordPress
- ✅ Funciona em instalações WordPress em subpastas
- ✅ Compatível com qualquer estrutura de permalink (Plain, Day and name, Month and name, Numeric, Post name, Custom)
- ✅ Redireciona apenas posts, não afeta páginas, CPTs ou uploads

## 🎯 Problema que Resolve

**Situação:** O site teve suas URLs padrão do WordPress atualizadas, causando vários erros 404.

**URLs Antigas:**
```
https://seusite.com/slug-do-post-teste/
https://seusite.com/post-de-teste-01/
https://seusite.com/outro-post-teste/0001/
```

**URLs Novas:**
```
https://seusite.com/slug-do-post-teste-59429/
https://seusite.com/post-de-teste-01-12345/
https://seusite.com/outro-post-teste-67890/
```

## ⚙️ Como Funciona

1. **Interceptação 404:** O plugin monitora todas as requisições que resultam em erro 404
2. **Extração do Slug:** Extrai o slug da URL (primeira parte após o domínio ou subpasta)
3. **Busca no Banco:** Busca no banco de dados WordPress por posts publicados com esse slug
4. **Redirecionamento 301:** Se encontrar o post, usa `get_permalink()` para redirecionar para a URL correta baseada nas **Configurações > Links permanentes**

### Detalhes Técnicos

- **Tipo de Redirecionamento:** 301 (permanente) - ideal para SEO
- **Tipos de Conteúdo:** Aplica-se apenas a posts (`post_type = 'post'`)
- **Exclusões:** Não processa URLs de admin, AJAX, feeds, CPTs, uploads ou imagens
- **Prevenção de Loops:** Verifica se já está na URL correta antes de redirecionar
- **Performance:** Retorna o primeiro post encontrado (ordenado por ID ascendente)
- **Estrutura de Permalinks:** Detecta automaticamente a estrutura configurada no WordPress
- **Subpastas:** Funciona perfeitamente em instalações WordPress em subdiretórios

## 📦 Instalação

### Método 1: Upload Manual

1. Faça o download do plugin (arquivo `wp-smart-404-redirect.php`)
2. Acesse o painel administrativo do WordPress
3. Vá em **Plugins > Adicionar Novo > Enviar Plugin**
4. Faça o upload do arquivo `.zip` (ou crie um compactando o arquivo PHP)
5. Clique em **Instalar Agora**
6. Após a instalação, clique em **Ativar**

### Método 2: Upload via FTP

1. Faça o download do arquivo `wp-smart-404-redirect.php`
2. Conecte-se ao seu servidor via FTP
3. Navegue até o diretório `/wp-content/plugins/`
4. Crie uma pasta chamada `wp-smart-404-redirect`
5. Faça o upload do arquivo `wp-smart-404-redirect.php` para dentro desta pasta
6. Acesse o painel do WordPress e vá em **Plugins**
7. Localize **WP Smart 404 Redirect** e clique em **Ativar**

## 🚀 Uso

O plugin funciona automaticamente após a ativação. Não há configurações adicionais necessárias.

### Exemplos Práticos

#### Exemplo 1: Instalação em Raiz com Permalink Customizado
**Configuração WordPress:** `/{postname}-{post_id}/`

**URL Acessada (404):**
```
https://seusite.com/slug-do-post-teste/
```

**O Plugin:**
- Extrai o slug: `slug-do-post-teste`
- Busca no banco de dados
- Encontra o post com ID `59429`
- Redireciona (301) para: `https://seusite.com/slug-do-post-teste-59429/`

#### Exemplo 2: Instalação em Subpasta
**Configuração WordPress:** `/{postname}-{post_id}/` em subpasta `/teste-post-404`

**URL Acessada (404):**
```
https://seusite.com/teste-post-404/hello-world/
```

**O Plugin:**
- Detecta a subpasta: `/teste-post-404`
- Extrai o slug: `hello-world`
- Busca no banco de dados
- Encontra o post com ID `1`
- Redireciona (301) para: `https://seusite.com/teste-post-404/hello-world-1/`

#### Exemplo 3: Estrutura de Permalink Diferente
**Configuração WordPress:** `/%year%/%monthnum%/%postname%/` (Day and name)

**URL Acessada (404):**
```
https://seusite.com/outro-post-teste/
```

**O Plugin:**
- Extrai o slug: `outro-post-teste`
- Busca no banco de dados
- Encontra o post com ID `67890` publicado em Janeiro de 2024
- Redireciona (301) para: `https://seusite.com/2024/01/outro-post-teste/` (conforme estrutura configurada)

#### Exemplo 4: Permalink Numérico
**Configuração WordPress:** `/?p={post_id}` (Plain)

**URL Acessada (404):**
```
https://seusite.com/meu-artigo/
```

**O Plugin:**
- Extrai o slug: `meu-artigo`
- Busca no banco de dados
- Encontra o post com ID `999`
- Redireciona (301) para: `https://seusite.com/?p=999`

## ✅ Requisitos

- **WordPress:** Versão 5.0 ou superior
- **PHP:** Versão 7.0 ou superior
- **Permissões:** Capacidade de escrever/modificar headers HTTP (para redirecionamento)

## 🔒 Segurança

O plugin implementa as seguintes medidas de segurança:

- ✅ Sanitização de todos os inputs usando funções nativas do WordPress
- ✅ Verificação de acesso direto ao arquivo PHP
- ✅ Uso do padrão Singleton para prevenir múltiplas instâncias
- ✅ Proteção contra redirecionamentos em loops
- ✅ Exclusão de áreas administrativas e AJAX
- ✅ Validação de objetos de post antes de redirecionar

## ❓ FAQ (Perguntas Frequentes)

### O plugin funciona com Custom Post Types (CPTs)?
Não. Por design, o plugin aplica-se apenas a posts padrão do WordPress (`post_type = 'post'`). URLs de CPTs, páginas, produtos WooCommerce, etc., não serão processadas.

### O plugin redireciona URLs de imagens ou uploads?
Não. O plugin ignora completamente URLs que não sejam de posts.

### O que acontece se houver múltiplos posts com o mesmo slug?
O plugin retornará o primeiro post encontrado, ordenado por ID (ascendente). Na prática, o WordPress não permite slugs duplicados para posts publicados, então este cenário é raro.

### O redirecionamento 301 afeta o SEO?
Sim, de forma positiva! O redirecionamento 301 (permanente) informa aos mecanismos de busca que a URL mudou permanentemente, transferindo o "valor" de SEO da URL antiga para a nova.

### O plugin mantém logs de redirecionamentos?
Não. Por questões de performance e simplicidade, o plugin não mantém logs. Se precisar monitorar redirecionamentos, recomenda-se usar plugins de analytics ou logs do servidor.

### O plugin funciona em sites multisite?
Sim, o plugin funciona em instalações multisite. Cada site da rede pode ativá-lo independentemente.

### Há impacto na performance do site?
O impacto é mínimo. O plugin só executa suas verificações quando ocorre um erro 404, e a query no banco de dados é otimizada com `no_found_rows` e `posts_per_page = 1`.

### O plugin funciona em instalações WordPress em subpastas?
Sim! O plugin detecta automaticamente se o WordPress está instalado em uma subpasta (como `/blog` ou `/teste-post-404`) e ajusta a extração do slug adequadamente.

### O plugin funciona com qualquer estrutura de permalink?
Sim! O plugin usa a função nativa `get_permalink()` do WordPress, que respeita automaticamente a estrutura configurada em **Configurações > Links permanentes**. Funciona com:
- **Plain:** `/?p=123`
- **Day and name:** `/%year%/%monthnum%/%day%/%postname%/`
- **Month and name:** `/%year%/%monthnum%/%postname%/`
- **Numeric:** `/%post_id%/`
- **Post name:** `/%postname%/`
- **Custom Structure:** qualquer estrutura personalizada (ex: `/%postname%-post_id%/`)

## 🛠️ Desenvolvimento

### Estrutura do Código

O plugin é construído usando uma classe principal (`WP_Smart_404_Redirect`) com o padrão Singleton:

- **`handle_404_redirect()`** - Função principal que gerencia o fluxo de redirecionamento
- **`extract_slug_from_url()`** - Extrai e sanitiza o slug da URL requisitada (com suporte a subpastas)
- **`find_post_by_slug()`** - Busca post no banco de dados usando WP_Query
- **`redirect_to_correct_url()`** - Usa `get_permalink()` para obter a URL correta conforme estrutura de permalinks configurada e executa o redirecionamento 301

### Hooks Utilizados

- `template_redirect` (prioridade 1) - Hook principal para interceptar requisições antes de carregar templates

## 📝 Licença

Este plugin é licenciado sob a GPL v2 ou superior.
