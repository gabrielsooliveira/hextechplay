# HextechPlay

O **HextechPlay** é uma aplicação web interativa focada no universo de League of Legends. A plataforma oferece diversos mini-games, visualização de times do cenário competitivo, e ferramentas adicionais para a comunidade, proporcionando uma experiência imersiva e responsiva para os fãs do jogo.

## 🌟 Funcionalidades

- **Mini-games Interativos:**
  - **WordLoL**: Um jogo no estilo Wordle focado no universo de LoL, com suporte para usuários anônimos e autenticados (perscrevendo progresso).
  - **Click Challenger**: Teste seus reflexos e habilidades de clique.
  - **Lore Questions**: Quiz sobre a rica história de Runeterra.
  - **Guess The Rank**: Tente adivinhar o elo das jogadas! (Acessível sem login).
- **LoL Team Visualizer**: Interface para visualizar times profissionais, listas de jogadores (rosters) e um painel com os títulos conquistados.
- **Blog**: Seção de artigos e conteúdo focada em SEO.
- **Documentação de API (Swagger)**: API totalmente documentada para os serviços da aplicação.
- **Autenticação**: Sistema completo de login, registro, e gerenciamento de perfis, com um layout premium baseado em Bootstrap.
- **Visualização de Eventos**: Telas públicas detalhadas sobre eventos, despesas e participação.

## 🛠 Tecnologias Utilizadas

Este projeto foi construído utilizando as ferramentas mais modernas do ecossistema PHP e JavaScript:

- **Backend:** [PHP 8.2](https://www.php.net/) / [Laravel 12.0](https://laravel.com/)
- **Frontend:** [Vue.js 3](https://vuejs.org/) / [Inertia.js](https://inertiajs.com/)
- **Estilização:** [Bootstrap 5](https://getbootstrap.com/) / Vanilla CSS / SASS
- **Banco de Dados:** MySQL (configurável no `.env`)
- **Build Tool:** [Vite](https://vitejs.dev/)
- **Documentação da API:** [L5-Swagger (OpenAPI)](https://github.com/DarkaOnLine/L5-Swagger)

## 📋 Pré-requisitos

Certifique-se de ter as seguintes ferramentas instaladas em seu ambiente de desenvolvimento:

- [PHP](https://www.php.net/downloads) (>= 8.2)
- [Composer](https://getcomposer.org/)
- [Node.js](https://nodejs.org/) & NPM
- Banco de dados MySQL (ou compatível)

## 🚀 Instalação e Configuração

Siga os passos abaixo para rodar o projeto localmente:

1. **Clone o repositório e acesse a pasta:**
   ```bash
   git clone <url-do-repositorio> hextechplay
   cd hextechplay
   ```

2. **Instale as dependências do PHP e do Node.js:**
   ```bash
   composer install
   npm install
   ```

3. **Configure as variáveis de ambiente:**
   Copie o arquivo de exemplo `.env.example` para `.env` e configure suas credenciais de banco de dados e outras opções.
   ```bash
   cp .env.example .env
   ```
   Abra o arquivo `.env` e certifique-se de configurar o banco de dados (por padrão `DB_DATABASE=lol_question`).

4. **Gere a chave da aplicação:**
   ```bash
   php artisan key:generate
   ```

5. **Rode as migrações (e os seeders, se houver):**
   ```bash
   php artisan migrate
   ```

## 💻 Rodando a Aplicação Localmente

A aplicação utiliza o Vite e o Laravel server. Você pode rodar tudo em paralelo com um único comando do NPM (configurado com o pacote `concurrently`):

```bash
npm run dev
```
*Este comando inicializa o servidor do Laravel, o queue listener e o Vite (HMR) ao mesmo tempo.*

Caso prefira rodar separadamente:
- Backend: `php artisan serve`
- Frontend: `npm run dev`

Acesse `http://localhost:8000` (ou a porta mostrada no terminal) em seu navegador.

## 📚 Documentação da API

A documentação interativa da API foi gerada utilizando o Swagger. Para acessá-la, basta subir o servidor do Laravel e navegar para a rota padrão do Swagger:

```
http://localhost:8000/api/documentation
```

## 🎮 Comandos Úteis

- **Definir Palavra Diária (WordLoL):**
  Para o funcionamento do minigame "WordLoL", existe um comando específico para configurar a palavra do dia no sistema:
  ```bash
  php artisan runeterra:set-daily-word
  ```

---

> Desenvolvido com foco em alta performance, código limpo e com as melhores práticas de Arquitetura de Software e UI/UX.
