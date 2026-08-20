<p align="center">
  <img src="https://img.icons8.com/isometric/512/holy-bible.png" alt="Bíblia Oficial Logo" width="120" />
</p>

<h1 align="center">📖 Bíblia Oficial API (RESTful)</h1>

<p align="center">
  <strong>API RESTful Oficial da Bíblia Sagrada em Português com Autenticação por Token</strong><br>
  <em>Base de dados independente com 3 versões consagradas, 66 livros canônicos e mais de 93.000 versículos.</em>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.1%20%7C%208.2%20%7C%208.3-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP" />
  <img src="https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL" />
  <img src="https://img.shields.io/badge/Auth-Bearer%20Token-orange?style=for-the-badge&logo=jsonwebtokens&logoColor=white" alt="Bearer Token" />
  <img src="https://img.shields.io/badge/Versículos-93.315-gold?style=for-the-badge&logo=bookstack&logoColor=black" alt="Versículos" />
  <img src="https://img.shields.io/badge/Status-100%25%20Online-success?style=for-the-badge" alt="Status" />
  <img src="https://img.shields.io/badge/License-MIT-blue?style=for-the-badge" alt="License" />
</p>

---

## 📑 Sumário

- [Visão Geral](#-visão-geral)
- [Diferenciais & Vantagens](#-diferenciais--vantagens)
- [Versões da Bíblia Disponíveis](#-versões-da-bíblia-disponíveis)
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
- [Instalação e Hospedagem Própria](#-instalação-e-hospedagem-própria)
- [Licença e Autor](#-licença-e-autor)

---

## 🌟 Visão Geral

A **Bíblia Oficial API** é uma solução RESTful moderna, leve e protegida para desenvolvedores, igrejas, ministérios e criadores de conteúdo que necessitam integrar as Sagradas Escrituras em seus aplicativos mobile, sites, sistemas web, bots de WhatsApp/Telegram ou assistentes de Inteligência Artificial.

Com a descontinuação de antigas APIs públicas, este projeto nasceu para ser a **fonte definitiva, gratuita e auto-hospedável da Bíblia Sagrada**, garantindo total autonomia, controle de consumo por Token e disponibilidade permanente.

---

## 🚀 Diferenciais & Vantagens

- 🔐 **Sistema de Tokens Seguro**: Controle de acesso individual com `Bearer Token` (`bbl_...`).
- ⚡ **Velocidade Extrema**: Respostas em menos de **10ms** com índices relacionais e busca `FULLTEXT`.
- 🌐 **Compatibilidade Universal**: Suporte total a CORS (`Access-Control-Allow-Origin: *`) para consumo direto por Single Page Applications (React, Vue, Angular, Flutter, React Native).
- 🔄 **Compatibilidade Retroativa**: Estrutura de dados 100% compatível com a antiga `abibliadigital`.
- 🛡️ **Zero Dependência Externa**: Banco de dados MySQL autônomo com todas as 3 versões completas embutidas.

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
- **Rota**: `/api/users`
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
- **Rota**: `/api/users/token`
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
- **Rota**: `/api/users/me`
- **Cabeçalho**: `Authorization: Bearer <SEU_TOKEN>`

#### Exemplo de Resposta (`200 OK`):
```json
{
  "id": 1,
  "name": "Seu Nome",
  "email": "seu.email@exemplo.com",
  "requests_count": 142,
  "last_request_at": "2026-08-20 19:30:15",
  "created_at": "2026-08-20 19:25:00"
}
```

---

## 📡 Endpoints da Bíblia

### 1. Listar Versões Disponíveis (Público)

- **Método**: `GET`
- **Rota**: `/api/versions`

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
- **Rota**: `/api/books`

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
- **Rota**: `/api/books/:abbrev` (*ex: `/api/books/sl`*)

#### Resposta (`200 OK`):
```json
{
  "abbrev": { "pt": "sl", "en": "ps" },
  "author": "Davi e outros",
  "chapters": 150,
  "group": "Poéticos",
  "name": "Salmos",
  "testament": "VT"
}
```

---

### 4. Obter Capítulo Completo (Requer Token)

- **Método**: `GET`
- **Rota**: `/api/verses/:version/:abbrev/:chapter`
- **Cabeçalho**: `Authorization: Bearer <SEU_TOKEN>`
- **Exemplo**: `GET /api/verses/nvi/sl/23`

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
- **Rota**: `/api/verses/:version/:abbrev/:chapter/:verse`
- **Cabeçalho**: `Authorization: Bearer <SEU_TOKEN>`
- **Exemplo**: `GET /api/verses/nvi/jo/3/16`

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
- **Rota**: `/api/verses/:version/random`
- **Cabeçalho**: `Authorization: Bearer <SEU_TOKEN>`
- **Exemplo**: `GET /api/verses/nvi/random`

#### Resposta (`200 OK`):
```json
{
  "book": {
    "name": "Filipenses",
    "abbrev": { "pt": "fp", "en": "php" }
  },
  "chapter": 4,
  "number": 13,
  "text": "Tudo posso naquele que me fortalece."
}
```

---

### 7. Busca por Palavras e Temas (Requer Token)

- **Método**: `POST` ou `GET`
- **Rota**: `/api/verses/search`
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
| 1 Samuel | `1sm` | Gálatas | `gl` |
| 2 Samuel | `2sm` | Efésios | `ef` |
| 1 Reis | `1rs` | Filipenses | `fp` |
| 2 Reis | `2rs` | Colossenses | `cl` |
| 1 Crônicas | `1cr` | 1 Tessalonicenses | `1ts` |
| 2 Crônicas | `2cr` | 2 Tessalonicenses | `2ts` |
| Esdras | `ed` | 1 Timóteo | `1tm` |
| Neemias | `ne` | 2 Timóteo | `2tm` |
| Ester | `et` | Tito | `tt` |
| Jó | `jó` | Filemom | `fm` |
| Salmos | `sl` | Hebreus | `hb` |
| Provérbios | `pv` | Tiago | `tg` |
| Eclesiastes | `ec` | 1 Pedro | `1pe` |
| Cânticos | `ct` | 2 Pedro | `2pe` |
| Isaías | `is` | 1 João | `1jo` |
| Jeremias | `jr` | 2 João | `2jo` |
| Lamentações | `lm` | 3 João | `3jo` |
| Ezequiel | `ez` | Judas | `jd` |
| Daniel | `dn` | Apocalipse | `ap` |
| Oséias a Malaquias | `os` .. `ml` | | |

---

## 💻 Exemplos de Integração em Código

### JavaScript / TypeScript (React / Node.js)

```typescript
const API_TOKEN = 'bbl_SEU_TOKEN_AQUI';

async function getVerse(version = 'nvi', abbrev = 'jo', chapter = 3, verse = 16) {
  const res = await fetch(`https://sua-api.com.br/api/verses/${version}/${abbrev}/${chapter}/${verse}`, {
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

TOKEN = "bbl_SEU_TOKEN_AQUI"
headers = {"Authorization": f"Bearer {TOKEN}"}

url = "https://sua-api.com.br/api/verses/nvi/sl/23"
res = requests.get(url, headers=headers)

if res.status_code == 200:
    capitulo = res.json()
    print(f"Capítulo {capitulo['chapter']['number']} de {capitulo['book']['name']}:")
    for v in capitulo['verses']:
        print(f"{v['number']}. {v['text']}")
```

---

### PHP (cURL)

```php
<?php
$token = "bbl_SEU_TOKEN_AQUI";

$ch = curl_init("https://sua-api.com.br/api/verses/nvi/random");
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer " . $token,
    "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);
echo "Versículo do Dia: " . $data['text'] . " (" . $data['book']['name'] . " " . $data['chapter'] . ":" . $data['number'] . ")\n";
```

---

### cURL (Terminal / Postman)

```bash
# 1. Gerar Token
curl -X POST "https://sua-api.com.br/api/users" \
     -H "Content-Type: application/json" \
     -d '{"name": "Rogerio", "email": "rogerio@email.com", "password": "123456Senha"}'

# 2. Consultar Versículo com Token
curl -X GET "https://sua-api.com.br/api/verses/nvi/jo/3/16" \
     -H "Authorization: Bearer bbl_SEU_TOKEN"
```

---

## 🚦 Códigos de Resposta HTTP

| Código | Significado | Descrição |
| :--- | :--- | :--- |
| `200 OK` | Sucesso | Requisição processada com sucesso. |
| `201 Created` | Criado | Usuário e token cadastrados com sucesso. |
| `401 Unauthorized` | Não Autorizado | Token ausente, inválido ou expirado. |
| `403 Forbidden` | Proibido | Conta inativa ou suspensa. |
| `404 Not Found` | Não Encontrado | Livro, capítulo ou versículo inexistente. |
| `409 Conflict` | Conflito | E-mail já cadastrado. |
| `500 Internal Error`| Erro no Servidor | Falha no banco de dados. |

---

## 🛠️ Instalação e Hospedagem Própria

1. Clone o repositório:
   ```bash
   git clone https://github.com/itadigitaloficial/bibliaoficial.git
   cd bibliaoficial
   ```
2. Configure o banco MySQL no `index.php` (`$dbConfig`).
3. Envie para seu servidor web Apache/Nginx ou cPanel!

---

## 📜 Licença e Autor

Distribuído sob a licença **MIT**.

Desenvolvido com excelência pela **Ita Digital Oficial**  
🌐 **Website**: [itadigital.com.br](https://itadigital.com.br) | [verses.itadigital.com.br](https://verses.itadigital.com.br)  
📦 **Repositório Oficial**: [github.com/itadigitaloficial/bibliaoficial](https://github.com/itadigitaloficial/bibliaoficial)
