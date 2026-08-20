<p align="center">
  <img src="https://img.icons8.com/isometric/512/holy-bible.png" alt="Bíblia Oficial Logo" width="120" />
</p>

<h1 align="center">📖 Bíblia Oficial API (RESTful)</h1>

<p align="center">
  <strong>API RESTful Oficial da Bíblia Sagrada em Português com Autenticação por Token</strong><br>
  <em>Base de dados independente com 3 versões consagradas, 66 livros canônicos e mais de 93.000 versículos.</em>
</p>

<p align="center">
  <a href="https://itadigital.com.br/api/biblia/">
    <img src="https://img.shields.io/badge/API%20Endpoint-https%3A%2F%2Fitadigital.com.br%2Fapi%2Fbiblia%2F-blueviolet?style=for-the-badge&logo=fastapi&logoColor=white" alt="API URL" />
  </a>
  <img src="https://img.shields.io/badge/PHP-8.1%20%7C%208.2%20%7C%208.3-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP" />
  <img src="https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL" />
  <img src="https://img.shields.io/badge/Auth-Bearer%20Token-orange?style=for-the-badge&logo=jsonwebtokens&logoColor=white" alt="Bearer Token" />
  <img src="https://img.shields.io/badge/Versículos-93.315-gold?style=for-the-badge&logo=bookstack&logoColor=black" alt="Versículos" />
</p>

---

## 🌐 URL Oficial da API em Produção

```http
https://itadigital.com.br/api/biblia/
```

---

## 📑 Sumário

- [Visão Geral](#-visão-geral)
- [Diferenciais & Vantagens](#-diferenciais--vantagens)
- [Versões da Bíblia Disponíveis](#-versões-da-biblia-disponíveis)
- [Autenticação & Tokens (Como Começar)](#-autenticação--tokens-como-começar)
  - [Passo 1: Criar Usuário & Obter Token](#passo-1-criar-usuário--obter-token)
  - [Passo 2: Recuperar Token Existente (Login)](#passo-2-recuperar-token-existente-login)
  - [Passo 3: Consultar Perfil e Estatísticas](#passo-3-consultar-perfil-e-estatísticas)
- [Endpoints da Bíblia](#-endpoints-da-bíblia)
  - [1. Listar Versões Disponíveis (Público)](#1-listar-versões-disponíveis-público)
  - [2. Listar Todos os 66 Livros (Público)](#2-listar-todos-os-66-livros-público)
  - [3. Detalhes de um Livro (Público)](#3-detalhes-de-um-livro-público)
  - [4. Obter Capítulo Completo (Requer Token)](#4-obter-capítulo-completo-requer-token)
  - [5. Obter Versículo Específico (Requer Token)](#5-obter-versículo-específico-requer-token)
  - [6. Versículo Aleatório do Dia (Requer Token)](#6-versículo-aleatório-do-dia-requer-token)
  - [7. Busca por Palavras e Temas (Requer Token)](#7-busca-por-palavras-e-temas-requer-token)
- [Tabela Canônica de Abreviações dos 66 Livros](#-tabela-canônica-de-abreviações-dos-66-livros)
- [Exemplos de Integração em Código](#-exemplos-de-integração-em-código)
  - [JavaScript / TypeScript (React / Node.js)](#javascript--typescript-react--nodejs)
  - [Python (Requests)](#python-requests)
  - [PHP (cURL)](#php-curl)
  - [cURL (Terminal / Postman)](#curl-terminal--postman)
- [Códigos de Resposta HTTP](#-códigos-de-resposta-http)
- [Como Hospedar em seu Servidor](#-como-hospedar-em-seu-servidor)
- [Licença e Autor](#-licença-e-autor)

---

## 🌟 Visão Geral

A **Bíblia Oficial API** é uma solução RESTful moderna, leve e protegida para desenvolvedores, igrejas, ministérios e criadores de conteúdo que necessitam integrar as Sagradas Escrituras em seus aplicativos mobile, sites, sistemas web, bots de WhatsApp/Telegram ou assistentes de Inteligência Artificial.

Hospedada oficialmente em `https://itadigital.com.br/api/biblia/`, ela oferece uma infraestrutura de alta disponibilidade com banco MySQL dedicado contendo **3 versões completas** e **mais de 93.000 versículos**.

---

## 🚀 Diferenciais & Vantagens

- 🔐 **Sistema de Tokens Seguro**: Controle de acesso individual com `Bearer Token` (`bbl_...`).
- ⚡ **Velocidade Extrema**: Respostas em menos de **10ms** com índices relacionais e busca `FULLTEXT`.
- 🌐 **Compatibilidade Universal**: Suporte total a CORS (`Access-Control-Allow-Origin: *`) para consumo direto por Single Page Applications (React, Vue, Angular, Flutter, React Native).
- 🔄 **Compatibilidade Retroativa**: Estrutura de dados 100% compatível com a antiga `abibliadigital`.
- 🛡️ **Zero Dependência Externa**: Banco de dados MySQL autônomo.

---

## 📖 Versões da Bíblia Disponíveis

| Código (`version`) | Nome da Tradução | Descrição | Total de Versículos |
| :--- | :--- | :--- | :--- |
| **`nvi`** | **Nova Versão Internacional** | Linguagem moderna, fluida e de fácil compreensão. | **31.105** |
| **`acf`** | **Almeida Corrigida Fiel** | Tradução clássica e reverente baseada no Texto Receptus. | **31.106** |
| **`aa`** | **Almeida Atualizada** | Versão consagrada de João Ferreira de Almeida revista. | **31.104** |

---

## 🔐 Autenticação & Tokens (Como Começar)

Para consumir os endpoints de versículos e busca, você precisará de um **Token de Acesso**. O processo de criação leva menos de 5 segundos:

### Passo 1: Criar Usuário & Obter Token

- **Método**: `POST`
- **URL**: `https://itadigital.com.br/api/biblia/users`
- **Acesso**: Público

#### Payload JSON:
```json
{
  "name": "Seu Nome ou Nome do App",
  "email": "seu.email@exemplo.com",
  "password": "suaSenhaSegura123"
}
```

#### Exemplo de Resposta (`201 Created`):
```json
{
  "message": "Usuário cadastrado com sucesso!",
  "user": {
    "id": 1,
    "name": "Seu Nome",
    "email": "seu.email@exemplo.com",
    "token": "bbl_e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855",
    "instructions": "Inclua este token no cabeçalho de todas as requisições: Authorization: Bearer bbl_..."
  }
}
```

---

### Passo 2: Recuperar Token Existente (Login)

Caso já possua cadastro e deseje recuperar seu Token:

- **Método**: `POST`
- **URL**: `https://itadigital.com.br/api/biblia/users/token`
- **Acesso**: Público

#### Payload JSON:
```json
{
  "email": "seu.email@exemplo.com",
  "password": "suaSenhaSegura123"
}
```

#### Exemplo de Resposta (`200 OK`):
```json
{
  "token": "bbl_e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855",
  "user": {
    "id": 1,
    "name": "Seu Nome",
    "email": "seu.email@exemplo.com"
  }
}
```

---

### Passo 3: Consultar Perfil e Estatísticas

- **Método**: `GET`
- **URL**: `https://itadigital.com.br/api/biblia/users/me`
- **Cabeçalho**: `Authorization: Bearer <SEU_TOKEN>`

---

## 📡 Endpoints da Bíblia

### 1. Listar Versões Disponíveis (Público)

- **Método**: `GET`
- **URL**: `https://itadigital.com.br/api/biblia/versions`

#### Resposta (`200 OK`):
```json
[
  {
    "id": "nvi",
    "name": "Nova Versão Internacional",
    "description": "Tradução moderna e de fácil compreensão da Bíblia",
    "language": "pt"
  },
  {
    "id": "acf",
    "name": "Almeida Corrigida Fiel",
    "description": "Tradução tradicional baseada no Texto Receptus",
    "language": "pt"
  },
  {
    "id": "aa",
    "name": "Almeida Atualizada",
    "description": "Tradução clássica de João Ferreira de Almeida",
    "language": "pt"
  }
]
```

---

### 2. Listar Todos os 66 Livros (Público)

- **Método**: `GET`
- **URL**: `https://itadigital.com.br/api/biblia/books`

#### Resposta (`200 OK`):
```json
[
  {
    "abbrev": { "pt": "gn", "en": "gn" },
    "author": "Moisés",
    "chapters": 50,
    "group": "Pentateuco",
    "name": "Gênesis",
    "testament": "VT"
  },
  {
    "abbrev": { "pt": "sl", "en": "ps" },
    "author": "Davi e outros",
    "chapters": 150,
    "group": "Poéticos",
    "name": "Salmos",
    "testament": "VT"
  },
  {
    "abbrev": { "pt": "jo", "en": "jn" },
    "author": "João",
    "chapters": 21,
    "group": "Evangelhos",
    "name": "João",
    "testament": "NT"
  }
]
```

---

### 3. Detalhes de um Livro (Público)

- **Método**: `GET`
- **URL**: `https://itadigital.com.br/api/biblia/books/:abbrev` (*ex: `https://itadigital.com.br/api/biblia/books/sl`*)

---

### 4. Obter Capítulo Completo (Requer Token)

- **Método**: `GET`
- **URL**: `https://itadigital.com.br/api/biblia/verses/:version/:abbrev/:chapter`
- **Cabeçalho**: `Authorization: Bearer <SEU_TOKEN>`
- **Exemplo**: `https://itadigital.com.br/api/biblia/verses/nvi/sl/23`

#### Resposta (`200 OK`):
```json
{
  "book": {
    "name": "Salmos",
    "author": "Davi e outros",
    "group": "Poéticos",
    "version": "NVI",
    "abbrev": { "pt": "sl", "en": "ps" }
  },
  "chapter": {
    "number": 23,
    "verses": 6
  },
  "verses": [
    { "number": 1, "text": "O Senhor é o meu pastor; de nada terei falta." },
    { "number": 2, "text": "Em verdes pastagens me faz repousar e me conduz a águas tranquilas;" },
    { "number": 3, "text": "restaura-me o vigor. Guia-me nas veredas da justiça por amor do seu nome." },
    { "number": 4, "text": "Mesmo quando eu andar por um vale de trevas e morte, não temerei perigo algum, pois tu estás comigo; a tua vara e o teu cajado me protegem." },
    { "number": 5, "text": "Preparas um banquete para mim à vista dos meus inimigos. Tu unges a minha cabeça com óleo, e o meu cálice transborda." },
    { "number": 6, "text": "Sei que a bondade e a fidelidade me acompanharão todos os dias da minha vida, e voltarei à casa do Senhor enquanto eu viver." }
  ]
}
```

---

### 5. Obter Versículo Específico (Requer Token)

- **Método**: `GET`
- **URL**: `https://itadigital.com.br/api/biblia/verses/:version/:abbrev/:chapter/:verse`
- **Cabeçalho**: `Authorization: Bearer <SEU_TOKEN>`
- **Exemplo**: `https://itadigital.com.br/api/biblia/verses/nvi/jo/3/16`

#### Resposta (`200 OK`):
```json
{
  "book": {
    "name": "João",
    "author": "João",
    "group": "Evangelhos",
    "abbrev": { "pt": "jo", "en": "jn" },
    "version": "NVI"
  },
  "chapter": 3,
  "number": 16,
  "text": "\"Porque Deus tanto amou o mundo que deu o seu Filho Unigênito, para que todo o que nele crer não pereça, mas tenha a vida eterna."
}
```

---

### 6. Versículo Aleatório do Dia (Requer Token)

- **Método**: `GET`
- **URL**: `https://itadigital.com.br/api/biblia/verses/:version/random`
- **Cabeçalho**: `Authorization: Bearer <SEU_TOKEN>`

---

### 7. Busca por Palavras e Temas (Requer Token)

- **Método**: `POST` ou `GET`
- **URL**: `https://itadigital.com.br/api/biblia/verses/search`
- **Cabeçalho**: `Authorization: Bearer <SEU_TOKEN>`

#### Payload (`POST`):
```json
{
  "version": "nvi",
  "search": "esperança"
}
```

---

## 📜 Tabela Canônica de Abreviações dos 66 Livros

| Antigo Testamento (39) | Abrev. | Novo Testamento (27) | Abrev. |
| :--- | :--- | :--- | :--- |
| Gênesis | `gn` | Mateus | `mt` |
| Êxodo | `ex` | Marcos | `mc` |
| Levítico | `lv` | Lucas | `lc` |
| Números | `nm` | João | `jo` |
| Deuteronômio | `dt` | Atos | `at` |
| Josué | `js` | Romanos | `rm` |
| Juízes | `jz` | 1 Coríntios | `1co` |
| Rute | `rt` | 2 Coríntios | `2co` |
| 1 Samuel | `1sm` | Cartas Paulinas | `gl` .. `fm` |
| Salmos | `sl` | Cartas Gerais | `hb` .. `jd` |
| Isaías | `is` | Apocalipse | `ap` |

---

## 💻 Exemplos de Integração em Código

### JavaScript / TypeScript (React / Node.js)

```typescript
const API_URL = 'https://itadigital.com.br/api/biblia';
const API_TOKEN = 'bbl_SEU_TOKEN_AQUI';

async function getVerse(version = 'nvi', abbrev = 'jo', chapter = 3, verse = 16) {
  const res = await fetch(`${API_URL}/verses/${version}/${abbrev}/${chapter}/${verse}`, {
    headers: {
      'Authorization': `Bearer ${API_TOKEN}`,
      'Content-Type': 'application/json'
    }
  });

  const data = await res.json();
  console.log(`${data.book.name} ${data.chapter}:${data.number} - "${data.text}"`);
}

getVerse();
```

---

### Python (Requests)

```python
import requests

API_URL = "https://itadigital.com.br/api/biblia"
TOKEN = "bbl_SEU_TOKEN_AQUI"
headers = {"Authorization": f"Bearer {TOKEN}"}

res = requests.get(f"{API_URL}/verses/nvi/sl/23", headers=headers)
if res.status_code == 200:
    data = res.json()
    print(f"{data['book']['name']} {data['chapter']['number']}:")
    for v in data['verses']:
        print(f"{v['number']}. {v['text']}")
```

---

### cURL (Terminal / Postman)

```bash
# 1. Gerar Token
curl -X POST "https://itadigital.com.br/api/biblia/users" \
     -H "Content-Type: application/json" \
     -d '{"name": "Rogerio", "email": "rogerio@email.com", "password": "123456Senha"}'

# 2. Consultar Salmos 23 com Token
curl -X GET "https://itadigital.com.br/api/biblia/verses/nvi/sl/23" \
     -H "Authorization: Bearer bbl_SEU_TOKEN"
```

---

## 🛠️ Como Hospedar em seu Servidor

1. Acesse o gerenciador de arquivos do cPanel ou seu servidor web em `itadigital.com.br`.
2. Crie a pasta `/api/biblia/` dentro do diretório raiz (`public_html/api/biblia/`).
3. Envie os arquivos `index.php` e `.htaccess` contidos nesta pasta.
4. Pronto! A API estará respondendo em `https://itadigital.com.br/api/biblia/`.

---

## 📜 Licença e Autor

Distribuído sob a licença **MIT**.

Desenvolvido com excelência pela **Ita Digital Oficial**  
🌐 **Website**: [itadigital.com.br](https://itadigital.com.br) | [verses.itadigital.com.br](https://verses.itadigital.com.br)  
📦 **Repositório Oficial**: [github.com/itadigitaloficial/bibliaoficial](https://github.com/itadigitaloficial/bibliaoficial)
