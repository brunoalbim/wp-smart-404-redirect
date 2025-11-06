# WP Smart 404 Redirect

Plugin WordPress que intercepta erros 404 e redireciona automaticamente para URLs corretas baseadas no slug do post.

## 📋 Descrição

Este plugin foi desenvolvido para resolver problemas de erro 404 causados por mudanças na estrutura de URLs de posts no WordPress. Quando um usuário acessa uma URL antiga que resulta em erro 404, o plugin:

1. Extrai o slug da URL requisitada
2. Busca no banco de dados por um post com esse slug
3. Redireciona automaticamente (301 - permanente) para a URL correta no novo formato

## 🎯 Problema que Resolve

**Situação:** O site teve suas URLs padrão do WordPress atualizadas, causando vários erros 404.

**URLs Antigas:**
```
https://diariodoestadogo.com.br/workshop-marca-dia-nacional-de-doacao-de-orgaos-em-goias/
https://diariodoestadogo.com.br/post-de-teste-01/
https://diariodoestadogo.com.br/outro-post-teste/0001/
```

**URLs Novas (com ID):**
```
https://diariodoestadogo.com.br/workshop-marca-dia-nacional-de-doacao-de-orgaos-em-goias-59429/
https://diariodoestadogo.com.br/post-de-teste-01-12345/
https://diariodoestadogo.com.br/outro-post-teste-67890/
```

## ⚙️ Como Funciona

1. **Interceptação 404:** O plugin monitora todas as requisições que resultam em erro 404
2. **Extração do Slug:** Extrai o slug da URL (primeira parte após o domínio)
3. **Busca no Banco:** Busca no banco de dados WordPress por posts publicados com esse slug
4. **Redirecionamento 301:** Se encontrar o post, redireciona para a URL correta no formato `{slug}-{ID}/`

### Detalhes Técnicos

- **Tipo de Redirecionamento:** 301 (permanente) - ideal para SEO
- **Tipos de Conteúdo:** Aplica-se apenas a posts (`post_type = 'post'`)
- **Exclusões:** Não processa URLs de admin, AJAX, feeds, CPTs, uploads ou imagens
- **Prevenção de Loops:** Verifica se já está na URL correta antes de redirecionar
- **Performance:** Retorna o primeiro post encontrado (ordenado por ID ascendente)

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

#### Exemplo 1: URL sem ID
**URL Acessada (404):**
```
https://diariodoestadogo.com.br/workshop-marca-dia-nacional-de-doacao-de-orgaos-em-goias/
```

**O Plugin:**
- Extrai o slug: `workshop-marca-dia-nacional-de-doacao-de-orgaos-em-goias`
- Busca no banco de dados
- Encontra o post com ID `59429`
- Redireciona (301) para: `https://diariodoestadogo.com.br/workshop-marca-dia-nacional-de-doacao-de-orgaos-em-goias-59429/`

#### Exemplo 2: URL com ID antigo/incorreto
**URL Acessada (404):**
```
https://diariodoestadogo.com.br/post-de-teste-01/
```

**O Plugin:**
- Extrai o slug: `post-de-teste-01`
- Busca no banco de dados
- Encontra o post com ID `12345`
- Redireciona (301) para: `https://diariodoestadogo.com.br/post-de-teste-01-12345/`

#### Exemplo 3: URL já com ID (mas página 404 por outro motivo)
**URL Acessada (404):**
```
https://diariodoestadogo.com.br/outro-post-teste/0001/
```

**O Plugin:**
- Extrai o slug: `outro-post-teste` (remove o ID `0001`)
- Busca no banco de dados
- Encontra o post com ID `67890`
- Redireciona (301) para: `https://diariodoestadogo.com.br/outro-post-teste-67890/`

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

## 🛠️ Desenvolvimento

### Estrutura do Código

O plugin é construído usando uma classe principal (`WP_Smart_404_Redirect`) com o padrão Singleton:

- **`handle_404_redirect()`** - Função principal que gerencia o fluxo de redirecionamento
- **`extract_slug_from_url()`** - Extrai e sanitiza o slug da URL requisitada
- **`find_post_by_slug()`** - Busca post no banco de dados usando WP_Query
- **`redirect_to_correct_url()`** - Constrói a URL correta e executa o redirecionamento 301

### Hooks Utilizados

- `template_redirect` (prioridade 1) - Hook principal para interceptar requisições antes de carregar templates

## 📝 Licença

Este plugin é licenciado sob a GPL v2 ou superior.

## 🤝 Suporte

Para suporte ou reportar problemas, entre em contato com a equipe de desenvolvimento DGO.

---

**Versão:** 1.0.0  
**Autor:** DGO Team  
**Compatível com:** WordPress 5.0+
