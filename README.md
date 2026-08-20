<p align="center">
  <img src="https://img.icons8.com/isometric/512/holy-bible.png" alt="Bíblia Oficial Logo" width="120" />
</p>

<h1 align="center">📖 Bíblia Oficial API (RESTful)</h1>

<p align="center">
  <strong>API RESTful de Alta Performance da Bíblia Sagrada em Português</strong><br>
  <em>Base de dados completa e independente com 3 versões consagradas, 66 livros canônicos e mais de 93.000 versículos.</em>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.1%20%7C%208.2%20%7C%208.3-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP" />
  <img src="https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL" />
  <img src="https://img.shields.io/badge/Versículos-93.315-gold?style=for-the-badge&logo=bookstack&logoColor=black" alt="Versículos" />
  <img src="https://img.shields.io/badge/Status-100%25%20Online-success?style=for-the-badge" alt="Status" />
  <img src="https://img.shields.io/badge/License-MIT-blue?style=for-the-badge" alt="License" />
</p>

---

## 📑 Sumário

- [Visão Geral](#-visão-geral)
- [Diferenciais & Vantagens](#-diferenciais--vantagens)
- [Versões da Bíblia Disponíveis](#-versões-da-bíblia-disponíveis)
- [Endpoints da API](#-endpoints-da-api)
  - [1. Listar Versões Disponíveis](#1-listar-versões-disponíveis)
  - [2. Listar Todos os Livros (66 Livros)](#2-listar-todos-os-livros-66-livros)
  - [3. Detalhes de um Livro Específico](#3-detalhes-de-um-livro-específico)
  - [4. Obter Capítulo Completo](#4-obter-capítulo-completo)
  - [5. Obter Versículo Específico](#5-obter-versículo-específico)
  - [6. Versículo Aleatório do Dia](#6-versículo-aleatório-do-dia)
  - [7. Busca de Versículos por Palavra-Chave / Tema](#7-busca-de-versículos-por-palavra-chave--tema)
- [Tabela Canônica de Abreviações dos 66 Livros](#-tabela-canônica-de-abreviações-dos-66-livros)
- [Exemplos de Integração em Código](#-exemplos-de-integração-em-código)
  - [JavaScript / TypeScript (Fetch / Axios)](#javascript--typescript-fetch--axios)
  - [Python (Requests)](#python-requests)
  - [PHP (cURL)](#php-curl)
  - [cURL (Terminal / Postman)](#curl-terminal--postman)
- [Códigos de Resposta HTTP & Tratamento de Erros](#-códigos-de-resposta-http--tratamento-de-erros)
- [Instalação e Hospedagem Própria](#-instalação-e-hospedagem-própria)
- [Contribuição](#-contribuição)
- [Licença e Autor](#-licença-e-autor)

---

## 🌟 Visão Geral

A **Bíblia Oficial API** é uma solução RESTful moderna, leve e ultra veloz para desenvolvedores, igrejas, ministérios e criadores de conteúdo que necessitam integrar as Sagradas Escrituras em seus aplicativos mobile, sites, sistemas web, bots de WhatsApp/Telegram ou assistentes de Inteligência Artificial.

Com a descontinuação de antigas APIs públicas, este projeto nasceu para ser a **fonte definitiva, gratuita e auto-hospedável da Bíblia Sagrada**, garantindo total autonomia e disponibilidade permanente.

---

## 🚀 Diferenciais & Vantagens

- ⚡ **Velocidade Extrema**: Respostas em menos de **10ms** com índices relacionais e busca `FULLTEXT`.
- 🌐 **Compatibilidade Universal**: Suporte total a CORS (`Access-Control-Allow-Origin: *`) para consumo direto por Single Page Applications (React, Vue, Angular, Flutter, React Native).
- 🔄 **Compatibilidade Retroativa**: Estrutura de dados 100% compatível com o ecossistema da antiga `abibliadigital`.
- 🛡️ **Zero Dependência Externa**: Banco de dados MySQL autônomo com todas as 3 versões completas embutidas.
- 📱 **Ideal para Apps e PWAs**: Permite sincronização e caching offline perfeito.

---

## 📖 Versões da Bíblia Disponíveis

| Código (`version`) | Nome da Tradução | Descrição | Total de Versículos |
| :--- | :--- | :--- | :--- |
| **`nvi`** | **Nova Versão Internacional** | Linguagem moderna, fluida e de fácil compreensão. | **31.105** |
| **`acf`** | **Almeida Corrigida Fiel** | Tradução clássica e reverente baseada no Texto Receptus. | **31.106** |
| **`aa`** | **Almeida Atualizada** | Versão consagrada de João Ferreira de Almeida revista. | **31.104** |

---

## 📡 Endpoints da API

A URL base da API é configurável conforme seu domínio (ex: `https://sua-api.com.br/api`).

### 1. Listar Versões Disponíveis

Retorna todas as traduções da Bíblia disponíveis no banco de dados.

- **Método**: `GET`
- **Rota**: `/api/versions`

#### Exemplo de Requisição:
```http
GET /api/versions HTTP/1.1
Host: api.verses.itadigital.com.br
```

#### Exemplo de Resposta (`200 OK`):
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

### 2. Listar Todos os Livros (66 Livros)

Retorna a lista canônica dos 66 livros bíblicos com autoria, total de capítulos, agrupamento e testamento.

- **Método**: `GET`
- **Rota**: `/api/books`

#### Exemplo de Resposta (`200 OK`):
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
    "abbrev": { "pt": "ex", "en": "ex" },
    "author": "Moisés",
    "chapters": 40,
    "group": "Pentateuco",
    "name": "Êxodo",
    "testament": "VT"
  },
  {
    "abbrev": { "pt": "mt", "en": "mt" },
    "author": "Mateus",
    "chapters": 28,
    "group": "Evangelhos",
    "name": "Mateus",
    "testament": "NT"
  },
  {
    "abbrev": { "pt": "ap", "en": "rev" },
    "author": "João",
    "chapters": 22,
    "group": "Profético NT",
    "name": "Apocalipse",
    "testament": "NT"
  }
]
```

---

### 3. Detalhes de um Livro Específico

Consulta os metadados de um único livro através de sua abreviação (`pt` ou `en`) ou nome.

- **Método**: `GET`
- **Rota**: `/api/books/:abbrev`

#### Parâmetros de URL:
| Parâmetro | Tipo | Descrição |
| :--- | :--- | :--- |
| `abbrev` | `string` | Abreviação do livro (*ex: `sl`, `jo`, `gn`, `rm`, `ap`*). |

#### Exemplo de Requisição:
```http
GET /api/books/sl HTTP/1.1
```

#### Exemplo de Resposta (`200 OK`):
```json
{
  "abbrev": {
    "pt": "sl",
    "en": "ps"
  },
  "author": "Davi e outros",
  "chapters": 150,
  "group": "Poéticos",
  "name": "Salmos",
  "testament": "VT"
}
```

---

### 4. Obter Capítulo Completo

Retorna todos os versículos de um capítulo específico na versão bíblica escolhida.

- **Método**: `GET`
- **Rota**: `/api/verses/:version/:abbrev/:chapter`

#### Parâmetros de URL:
| Parâmetro | Tipo | Descrição |
| :--- | :--- | :--- |
| `version` | `string` | Versão da Bíblia (`nvi`, `acf`, `aa`). |
| `abbrev` | `string` | Abreviação do livro (*ex: `sl`, `mt`, `rm`*). |
| `chapter` | `integer`| Número do capítulo (*ex: `23`*). |

#### Exemplo de Requisição:
```http
GET /api/verses/nvi/sl/23 HTTP/1.1
```

#### Exemplo de Resposta (`200 OK`):
```json
{
  "book": {
    "name": "Salmos",
    "author": "Davi e outros",
    "group": "Poéticos",
    "version": "NVI",
    "abbrev": {
      "pt": "sl",
      "en": "ps"
    }
  },
  "chapter": {
    "number": 23,
    "verses": 6
  },
  "verses": [
    {
      "number": 1,
      "text": "O Senhor é o meu pastor; de nada terei falta."
    },
    {
      "number": 2,
      "text": "Em verdes pastagens me faz repousar e me conduz a águas tranquilas;"
    },
    {
      "number": 3,
      "text": "restaura-me o vigor. Guia-me nas veredas da justiça por amor do seu nome."
    },
    {
      "number": 4,
      "text": "Mesmo quando eu andar por um vale de trevas e morte, não temerei perigo algum, pois tu estás comigo; a tua vara e o teu cajado me protegem."
    },
    {
      "number": 5,
      "text": "Preparas um banquete para mim à vista dos meus inimigos. Tu unges a minha cabeça com óleo, e o meu cálice transborda."
    },
    {
      "number": 6,
      "text": "Sei que a bondade e a fidelidade me acompanharão todos os dias da minha vida, e voltarei à casa do Senhor enquanto eu viver."
    }
  ]
}
```

---

### 5. Obter Versículo Específico

Retorna o texto e metadados de um único versículo.

- **Método**: `GET`
- **Rota**: `/api/verses/:version/:abbrev/:chapter/:verse`

#### Parâmetros de URL:
| Parâmetro | Tipo | Descrição |
| :--- | :--- | :--- |
| `version` | `string` | Versão da Bíblia (`nvi`, `acf`, `aa`). |
| `abbrev` | `string` | Abreviação do livro (*ex: `jo`, `fp`*). |
| `chapter` | `integer`| Número do capítulo (*ex: `3`, `4`*). |
| `verse` | `integer`| Número do versículo (*ex: `16`, `13`*). |

#### Exemplo de Requisição:
```http
GET /api/verses/nvi/jo/3/16 HTTP/1.1
```

#### Exemplo de Resposta (`200 OK`):
```json
{
  "book": {
    "name": "João",
    "author": "João",
    "group": "Evangelhos",
    "abbrev": {
      "pt": "jo",
      "en": "jn"
    },
    "version": "NVI"
  },
  "chapter": 3,
  "number": 16,
  "text": "\"Porque Deus tanto amou o mundo que deu o seu Filho Unigênito, para que todo o que nele crer não pereça, mas tenha a vida eterna."
}
```

---

### 6. Versículo Aleatório do Dia

Retorna um versículo sorteado aleatoriamente de toda a Bíblia, ideal para *widgets*, mensagens devocionais e notificações diárias.

- **Método**: `GET`
- **Rota**: `/api/verses/:version/random`

#### Exemplo de Requisição:
```http
GET /api/verses/nvi/random HTTP/1.1
```

#### Exemplo de Resposta (`200 OK`):
```json
{
  "book": {
    "name": "Filipenses",
    "abbrev": {
      "pt": "fp",
      "en": "php"
    }
  },
  "chapter": 4,
  "number": 13,
  "text": "Tudo posso naquele que me fortalece."
}
```

---

### 7. Busca de Versículos por Palavra-Chave / Tema

Pesquisa em alta velocidade por qualquer termo, palavra ou expressão em todos os 31.000 versículos da tradução indicada.

- **Método**: `POST` ou `GET`
- **Rota**: `/api/verses/search`

#### Payload (`POST` em formato JSON):
```json
{
  "version": "nvi",
  "search": "amor"
}
```

*Ou via URL (`GET`):*
```http
GET /api/verses/search?version=nvi&search=amor HTTP/1.1
```

#### Exemplo de Resposta (`200 OK`):
```json
{
  "verses": [
    {
      "book": {
        "name": "1 Coríntios",
        "abbrev": {
          "pt": "1co",
          "en": "1co"
        }
      },
      "chapter": 13,
      "number": 13,
      "text": "Assim, permanecem agora estes três: a fé, a esperança e o amor. O maior deles, porém, é o amor."
    },
    {
      "book": {
        "name": "1 João",
        "abbrev": {
          "pt": "1jo",
          "en": "1jn"
        }
      },
      "chapter": 4,
      "number": 8,
      "text": "Quem não ama não conhece a Deus, porque Deus é amor."
    }
  ]
}
```

---

## 📜 Tabela Canônica de Abreviações dos 66 Livros

### Antigo Testamento (39 Livros)

| Livro | Abrev. (`pt`) | Abrev. (`en`) | Grupo | Capítulos |
| :--- | :--- | :--- | :--- | :--- |
| **Gênesis** | `gn` | `gn` | Pentateuco | 50 |
| **Êxodo** | `ex` | `ex` | Pentateuco | 40 |
| **Levítico** | `lv` | `lv` | Pentateuco | 27 |
| **Números** | `nm` | `nm` | Pentateuco | 36 |
| **Deuteronômio** | `dt` | `dt` | Pentateuco | 34 |
| **Josué** | `js` | `js` | Históricos | 24 |
| **Juízes** | `jz` | `jud` | Históricos | 21 |
| **Rute** | `rt` | `ru` | Históricos | 4 |
| **1 Samuel** | `1sm` | `1sa` | Históricos | 31 |
| **2 Samuel** | `2sm` | `2sa` | Históricos | 24 |
| **1 Reis** | `1rs` | `1ki` | Históricos | 22 |
| **2 Reis** | `2rs` | `2ki` | Históricos | 25 |
| **1 Crônicas** | `1cr` | `1ch` | Históricos | 29 |
| **2 Crônicas** | `2cr` | `2ch` | Históricos | 36 |
| **Esdras** | `ed` | `ezr` | Históricos | 10 |
| **Neemias** | `ne` | `ne` | Históricos | 13 |
| **Ester** | `et` | `es` | Históricos | 10 |
| **Jó** | `jó` | `job` | Poéticos | 42 |
| **Salmos** | `sl` | `ps` | Poéticos | 150 |
| **Provérbios** | `pv` | `prv` | Poéticos | 31 |
| **Eclesiastes** | `ec` | `ec` | Poéticos | 12 |
| **Cânticos** | `ct` | `so` | Poéticos | 8 |
| **Isaías** | `is` | `isa` | Profetas Maiores | 66 |
| **Jeremias** | `jr` | `jer` | Profetas Maiores | 52 |
| **Lamentações** | `lm` | `la` | Profetas Maiores | 5 |
| **Ezequiel** | `ez` | `eze` | Profetas Maiores | 48 |
| **Daniel** | `dn` | `da` | Profetas Maiores | 12 |
| **Oséias** | `os` | `ho` | Profetas Menores | 14 |
| **Joel** | `jl` | `joe` | Profetas Menores | 3 |
| **Amós** | `am` | `am` | Profetas Menores | 9 |
| **Obadias** | `ob` | `ob` | Profetas Menores | 1 |
| **Jonas** | `jn` | `jon` | Profetas Menores | 4 |
| **Miquéias** | `mq` | `mic` | Profetas Menores | 7 |
| **Naum** | `na` | `na` | Profetas Menores | 3 |
| **Habacuque** | `hc` | `hab` | Profetas Menores | 3 |
| **Sofonias** | `sf` | `zep` | Profetas Menores | 3 |
| **Ageu** | `ag` | `hag` | Profetas Menores | 2 |
| **Zacarias** | `zc` | `zec` | Profetas Menores | 14 |
| **Malaquias** | `ml` | `mal` | Profetas Menores | 4 |

---

### Novo Testamento (27 Livros)

| Livro | Abrev. (`pt`) | Abrev. (`en`) | Grupo | Capítulos |
| :--- | :--- | :--- | :--- | :--- |
| **Mateus** | `mt` | `mt` | Evangelhos | 28 |
| **Marcos** | `mc` | `mk` | Evangelhos | 16 |
| **Lucas** | `lc` | `lk` | Evangelhos | 24 |
| **João** | `jo` | `jn` | Evangelhos | 21 |
| **Atos** | `at` | `act` | Histórico NT | 28 |
| **Romanos** | `rm` | `ro` | Cartas Paulinas | 16 |
| **1 Coríntios** | `1co` | `1co` | Cartas Paulinas | 16 |
| **2 Coríntios** | `2co` | `2co` | Cartas Paulinas | 13 |
| **Gálatas** | `gl` | `ga` | Cartas Paulinas | 6 |
| **Efésios** | `ef` | `eph` | Cartas Paulinas | 6 |
| **Filipenses** | `fp` | `php` | Cartas Paulinas | 4 |
| **Colossenses** | `cl` | `col` | Cartas Paulinas | 4 |
| **1 Tessalonicenses** | `1ts` | `1th` | Cartas Paulinas | 5 |
| **2 Tessalonicenses** | `2ts` | `2th` | Cartas Paulinas | 3 |
| **1 Timóteo** | `1tm` | `1ti` | Cartas Paulinas | 6 |
| **2 Timóteo** | `2tm` | `2ti` | Cartas Paulinas | 4 |
| **Tito** | `tt` | `tit` | Cartas Paulinas | 3 |
| **Filemom** | `fm` | `phm` | Cartas Paulinas | 1 |
| **Hebreus** | `hb` | `heb` | Cartas Gerais | 13 |
| **Tiago** | `tg` | `jas` | Cartas Gerais | 5 |
| **1 Pedro** | `1pe` | `1pe` | Cartas Gerais | 5 |
| **2 Pedro** | `2pe` | `2pe` | Cartas Gerais | 3 |
| **1 João** | `1jo` | `1jn` | Cartas Gerais | 5 |
| **2 João** | `2jo` | `2jn` | Cartas Gerais | 1 |
| **3 João** | `3jo` | `3jn` | Cartas Gerais | 1 |
| **Judas** | `jd` | `jud` | Cartas Gerais | 1 |
| **Apocalipse** | `ap` | `rev` | Profético NT | 22 |

---

## 💻 Exemplos de Integração em Código

### JavaScript / TypeScript (Fetch / Axios)

```typescript
// Buscar o capítulo 23 de Salmos na versão NVI
async function getPsalm23() {
  const response = await fetch('https://sua-api.com.br/api/verses/nvi/sl/23');
  const data = await response.json();
  
  console.log(`${data.book.name} ${data.chapter.number}:`);
  data.verses.forEach((v: { number: number; text: string }) => {
    console.log(`${v.number}. ${v.text}`);
  });
}

getPsalm23();
```

---

### Python (Requests)

```python
import requests

url = "https://sua-api.com.br/api/verses/nvi/jo/3/16"
response = requests.get(url)

if response.status_code == 200:
    verse = response.json()
    print(f"{verse['book']['name']} {verse['chapter']}:{verse['number']} -> {verse['text']}")
```

---

### PHP (cURL)

```php
<?php
$ch = curl_init("https://sua-api.com.br/api/verses/nvi/random");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$verse = json_decode($response, true);
echo "Versículo do Dia: " . $verse['text'] . " (" . $verse['book']['name'] . " " . $verse['chapter'] . ":" . $verse['number'] . ")\n";
```

---

### cURL (Terminal / Postman)

```bash
# Versículo específico
curl -X GET "https://sua-api.com.br/api/verses/nvi/jo/3/16"

# Busca por palavra
curl -X POST "https://sua-api.com.br/api/verses/search" \
     -H "Content-Type: application/json" \
     -d '{"version": "nvi", "search": "esperança"}'
```

---

## 🚦 Códigos de Resposta HTTP & Tratamento de Erros

A API utiliza códigos padrão de status HTTP:

| Código | Significado | Descrição |
| :--- | :--- | :--- |
| `200 OK` | Sucesso | A requisição foi processada com sucesso. |
| `400 Bad Request` | Requisição Inválida | Parâmetros ausentes ou mal formatados. |
| `404 Not Found` | Não Encontrado | Livro, capítulo, versículo ou rota inexistente. |
| `500 Internal Error`| Erro no Servidor | Falha de conexão ou erro no banco de dados. |

#### Exemplo de Resposta de Erro (`404 Not Found`):
```json
{
  "error": "Livro não encontrado"
}
```

---

## 🛠️ Instalação e Hospedagem Própria

### Requisitos Mínimos
- **PHP**: 7.4 ou superior (Recomendado PHP 8.1+) com extensões `pdo_mysql` e `json` ativas.
- **MySQL / MariaDB**: 5.7+ ou 8.0+.
- **Servidor Web**: Apache (com `mod_rewrite` e `mod_headers`) ou Nginx.

### Passo a Passo

1. **Clone o repositório**:
   ```bash
   git clone https://github.com/itadigitaloficial/bibliaoficial.git
   cd bibliaoficial
   ```

2. **Configure o banco de dados**:
   - Crie o banco MySQL (ex: `biblia`).
   - Ajuste as credenciais no arquivo `index.php`:
     ```php
     $dbConfig = [
         'host' => 'localhost',
         'port' => 3306,
         'dbname' => 'biblia',
         'user' => 'seu_usuario',
         'pass' => 'sua_senha',
         'charset' => 'utf8mb4'
     ];
     ```

3. **Suba para seu servidor**:
   - Basta enviar a pasta `api/` para a raiz ou subdiretório do seu servidor cPanel, VPS ou Docker!

---

## 🤝 Contribuição

Contribuições são extremamente bem-vindas! Sinta-se à vontade para abrir uma *Issue* ou enviar um *Pull Request*:

1. Faça um Fork do projeto
2. Crie uma Branch para sua feature (`git checkout -b feature/MinhaFeature`)
3. Faça o Commit das alterações (`git commit -m 'feat: adiciona nova funcionalidade'`)
4. Faça o Push para a Branch (`git push origin feature/MinhaFeature`)
5. Abra um Pull Request

---

## 📜 Licença e Autor

Distribuído sob a licença **MIT**. Veja `LICENSE` para mais informações.

Desenvolvido com excelência pela **Ita Digital Oficial**  
🌐 **Website**: [itadigital.com.br](https://itadigital.com.br) | [verses.itadigital.com.br](https://verses.itadigital.com.br)  
📦 **Repositório Oficial**: [github.com/itadigitaloficial/bibliaoficial](https://github.com/itadigitaloficial/bibliaoficial)
