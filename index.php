<?php
/**
 * 📖 Bíblia Oficial API (PHP + MySQL)
 * RESTful API com Autenticação por Token (Bearer Token)
 * URL Oficial: https://itadigital.com.br/api/biblia/
 * Repositório: https://github.com/itadigitaloficial/bibliaoficial
 */

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// 1. Configuração do Banco de Dados
$dbConfig = [
    'host' => '153.75.245.98', // Altere para 127.0.0.1 se o MySQL estiver na mesma máquina
    'port' => 3306,
    'dbname' => 'biblia',
    'user' => 'bibliaitadigital',
    'pass' => '01HHDl1-PHFPyxUq',
    'charset' => 'utf8mb4'
];

try {
    $dsn = "mysql:host={$dbConfig['host']};port={$dbConfig['port']};dbname={$dbConfig['dbname']};charset={$dbConfig['charset']}";
    $pdo = new PDO($dsn, $dbConfig['user'], $dbConfig['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Falha de conexão com o banco de dados: ' . $e->getMessage()]);
    exit;
}

// 2. Funções Auxiliares de Autenticação
function getBearerToken() {
    $headers = null;
    if (isset($_SERVER['Authorization'])) {
        $headers = trim($_SERVER['Authorization']);
    } elseif (isset($_SERVER['HTTP_AUTHORIZATION'])) {
        $headers = trim($_SERVER['HTTP_AUTHORIZATION']);
    } elseif (function_exists('apache_request_headers')) {
        $requestHeaders = apache_request_headers();
        $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
        if (isset($requestHeaders['Authorization'])) {
            $headers = trim($requestHeaders['Authorization']);
        }
    }

    if (!empty($headers) && preg_match('/Bearer\s(\S+)/i', $headers, $matches)) {
        return $matches[1];
    }
    return null;
}

function requireAuth($pdo) {
    $token = getBearerToken();

    if (!$token) {
        http_response_code(401);
        echo json_encode([
            'error' => 'Acesso não autorizado',
            'message' => 'Forneça um token válido no cabeçalho: Authorization: Bearer <SEU_TOKEN>',
            'docs' => 'https://github.com/itadigitaloficial/bibliaoficial#autenticação--tokens'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $stmt = $pdo->prepare("SELECT id, name, email, is_active, requests_count FROM api_users WHERE token = ?");
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if (!$user || (int)$user['is_active'] !== 1) {
        http_response_code(403);
        echo json_encode([
            'error' => 'Token inválido ou inativo',
            'message' => 'Gere um novo token em POST /api/biblia/users/token'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // Atualizar contador e timestamp do usuário de forma assíncrona
    $stmtUpdate = $pdo->prepare("UPDATE api_users SET requests_count = requests_count + 1, last_request_at = NOW() WHERE id = ?");
    $stmtUpdate->execute([$user['id']]);

    return $user;
}

function getJsonBody() {
    $input = file_get_contents('php://input');
    return json_decode($input, true) ?: [];
}

// 3. Roteador Flexível (Funciona na raiz, /api, /api/biblia ou subpastas)
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($scriptDir !== '/' && strpos($uri, $scriptDir) === 0) {
    $uri = substr($uri, strlen($scriptDir));
}

// Limpar possíveis prefixos de subpastas
$uri = preg_replace('#^/api/biblia#i', '', $uri);
$uri = preg_replace('#^/api#i', '', $uri);
$uri = preg_replace('#^/biblia#i', '', $uri);
$segments = array_values(array_filter(explode('/', $uri)));

$method = $_SERVER['REQUEST_METHOD'];

// Rota Raiz: Informações Gerais da API
if (empty($segments)) {
    echo json_encode([
        'name' => 'Bíblia Oficial API (RESTful)',
        'version' => '1.1.0',
        'status' => 'online',
        'base_url' => 'https://itadigital.com.br/api/biblia',
        'documentation' => 'https://github.com/itadigitaloficial/bibliaoficial',
        'endpoints' => [
            'POST /users' => 'Criar conta de usuário e obter Token (Público)',
            'POST /users/token' => 'Obter Token de acesso via Email e Senha (Público)',
            'GET /users/me' => 'Consultar dados do seu usuário (Requer Token)',
            'GET /versions' => 'Lista as versões disponíveis (Público)',
            'GET /books' => 'Lista os 66 livros da Bíblia (Público)',
            'GET /books/{abbrev}' => 'Detalhes de um livro (Público)',
            'GET /verses/{version}/{abbrev}/{chapter}' => 'Versículos do capítulo (Requer Token)',
            'GET /verses/{version}/{abbrev}/{chapter}/{verse}' => 'Versículo específico (Requer Token)',
            'GET /verses/{version}/random' => 'Versículo aleatório do dia (Requer Token)',
            'POST /verses/search' => 'Busca de versículos por palavra (Requer Token)'
        ]
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

// ==========================================
// 4. ROTAS DE USUÁRIOS E AUTENTICAÇÃO (TOKEN)
// ==========================================

// POST /users (Criar Conta & Gerar Token)
if ($segments[0] === 'users' && (!isset($segments[1]) || $segments[1] === 'register') && $method === 'POST') {
    $data = getJsonBody();
    $name = trim($data['name'] ?? '');
    $email = strtolower(trim($data['email'] ?? ''));
    $password = trim($data['password'] ?? '');

    if (empty($name) || strlen($name) < 2) {
        http_response_code(400);
        echo json_encode(['error' => 'O nome é obrigatório (mínimo 2 caracteres).'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['error' => 'Forneça um endereço de e-mail válido.'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    if (empty($password) || strlen($password) < 6) {
        http_response_code(400);
        echo json_encode(['error' => 'A senha é obrigatória (mínimo 6 caracteres).'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // Verificar se e-mail já existe
    $stmt = $pdo->prepare("SELECT id FROM api_users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        http_response_code(409);
        echo json_encode(['error' => 'Este e-mail já está cadastrado. Utilize POST /users/token para recuperar seu token.'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // Gerar token seguro com prefixo bbl_
    $token = 'bbl_' . bin2hex(random_bytes(24));
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    $stmtInsert = $pdo->prepare("INSERT INTO api_users (name, email, password_hash, token) VALUES (?, ?, ?, ?)");
    $stmtInsert->execute([$name, $email, $passwordHash, $token]);
    $userId = (int)$pdo->lastInsertId();

    http_response_code(201);
    echo json_encode([
        'message' => 'Usuário cadastrado com sucesso!',
        'user' => [
            'id' => $userId,
            'name' => $name,
            'email' => $email,
            'token' => $token,
            'instructions' => 'Inclua este token no cabeçalho de todas as requisições: Authorization: Bearer ' . $token
        ]
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

// POST /users/token (Login / Obter Token)
if ($segments[0] === 'users' && isset($segments[1]) && $segments[1] === 'token' && $method === 'POST') {
    $data = getJsonBody();
    $email = strtolower(trim($data['email'] ?? ''));
    $password = trim($data['password'] ?? '');

    if (empty($email) || empty($password)) {
        http_response_code(400);
        echo json_encode(['error' => 'E-mail e senha são obrigatórios.'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $stmt = $pdo->prepare("SELECT id, name, email, password_hash, token, is_active FROM api_users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password_hash'])) {
        http_response_code(401);
        echo json_encode(['error' => 'E-mail ou senha incorretos.'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    if ((int)$user['is_active'] !== 1) {
        http_response_code(403);
        echo json_encode(['error' => 'Esta conta está suspensa ou inativa.'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    echo json_encode([
        'token' => $user['token'],
        'user' => [
            'id' => (int)$user['id'],
            'name' => $user['name'],
            'email' => $user['email']
        ]
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// GET /users/me (Consultar Perfil - Requer Token)
if ($segments[0] === 'users' && isset($segments[1]) && $segments[1] === 'me' && $method === 'GET') {
    $user = requireAuth($pdo);
    
    $stmt = $pdo->prepare("SELECT id, name, email, requests_count, last_request_at, created_at FROM api_users WHERE id = ?");
    $stmt->execute([$user['id']]);
    $profile = $stmt->fetch();

    echo json_encode($profile, JSON_UNESCAPED_UNICODE);
    exit;
}

// ==========================================
// 5. ROTAS DE METADADOS (PÚBLICAS)
// ==========================================

// GET /versions
if ($segments[0] === 'versions' && $method === 'GET') {
    $stmt = $pdo->query("SELECT id, name, description, language FROM bible_versions ORDER BY id");
    echo json_encode($stmt->fetchAll(), JSON_UNESCAPED_UNICODE);
    exit;
}

// GET /books ou GET /books/{abbrev}
if ($segments[0] === 'books' && $method === 'GET') {
    if (isset($segments[1])) {
        $abbrev = strtolower(trim($segments[1]));
        if ($abbrev === 'atos') $abbrev = 'at';
        if ($abbrev === 'job') $abbrev = 'jó';

        $stmt = $pdo->prepare("SELECT book_number, abbrev_pt, abbrev_en, name, author, group_name, testament, total_chapters FROM bible_books WHERE abbrev_pt = ? OR abbrev_en = ? OR name = ?");
        $stmt->execute([$abbrev, $abbrev, $abbrev]);
        $book = $stmt->fetch();

        if (!$book) {
            http_response_code(404);
            echo json_encode(['error' => 'Livro não encontrado'], JSON_UNESCAPED_UNICODE);
            exit;
        }

        echo json_encode([
            'abbrev' => ['pt' => $book['abbrev_pt'], 'en' => $book['abbrev_en']],
            'author' => $book['author'],
            'chapters' => (int)$book['total_chapters'],
            'group' => $book['group_name'],
            'name' => $book['name'],
            'testament' => $book['testament']
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $stmt = $pdo->query("SELECT book_number, abbrev_pt, abbrev_en, name, author, group_name, testament, total_chapters FROM bible_books ORDER BY book_number");
    $books = $stmt->fetchAll();

    $result = array_map(function ($b) {
        return [
            'abbrev' => ['pt' => $b['abbrev_pt'], 'en' => $b['abbrev_en']],
            'author' => $b['author'],
            'chapters' => (int)$b['total_chapters'],
            'group' => $b['group_name'],
            'name' => $b['name'],
            'testament' => $b['testament']
        ];
    }, $books);

    echo json_encode($result, JSON_UNESCAPED_UNICODE);
    exit;
}

// ==========================================
// 6. ROTAS DE VERSÍCULOS (REQUEREM TOKEN)
// ==========================================

// GET /verses/{version}/random
if ($segments[0] === 'verses' && isset($segments[1]) && isset($segments[2]) && $segments[2] === 'random') {
    requireAuth($pdo);
    $version = strtolower(trim($segments[1]));
    
    $stmt = $pdo->prepare("
        SELECT v.chapter, v.verse, v.text, b.name as book_name, b.abbrev_pt, b.abbrev_en
        FROM bible_verses v
        JOIN bible_books b ON v.book_id = b.id
        WHERE v.version_id = ?
        ORDER BY RAND()
        LIMIT 1
    ");
    $stmt->execute([$version]);
    $row = $stmt->fetch();

    if (!$row) {
        http_response_code(404);
        echo json_encode(['error' => 'Nenhum versículo encontrado'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    echo json_encode([
        'book' => [
            'name' => $row['book_name'],
            'abbrev' => ['pt' => $row['abbrev_pt'], 'en' => $row['abbrev_en']]
        ],
        'chapter' => (int)$row['chapter'],
        'number' => (int)$row['verse'],
        'text' => $row['text']
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// POST /verses/search ou GET /verses/search?version=nvi&search=termo
if ($segments[0] === 'verses' && isset($segments[1]) && $segments[1] === 'search') {
    requireAuth($pdo);
    $version = 'nvi';
    $search = '';

    if ($method === 'POST') {
        $body = getJsonBody();
        $version = strtolower(trim($body['version'] ?? 'nvi'));
        $search = trim($body['search'] ?? '');
    } else {
        $version = strtolower(trim($_GET['version'] ?? 'nvi'));
        $search = trim($_GET['search'] ?? '');
    }

    if (empty($search)) {
        echo json_encode(['verses' => []]);
        exit;
    }

    $stmt = $pdo->prepare("
        SELECT v.chapter, v.verse, v.text, b.name as book_name, b.abbrev_pt, b.abbrev_en
        FROM bible_verses v
        JOIN bible_books b ON v.book_id = b.id
        WHERE v.version_id = ? AND v.text LIKE ?
        LIMIT 100
    ");
    $stmt->execute([$version, "%{$search}%"]);
    $rows = $stmt->fetchAll();

    $verses = array_map(function ($r) {
        return [
            'book' => [
                'name' => $r['book_name'],
                'abbrev' => ['pt' => $r['abbrev_pt'], 'en' => $r['abbrev_en']]
            ],
            'chapter' => (int)$r['chapter'],
            'number' => (int)$r['verse'],
            'text' => $r['text']
        ];
    }, $rows);

    echo json_encode(['verses' => $verses], JSON_UNESCAPED_UNICODE);
    exit;
}

// GET /verses/{version}/{abbrev}/{chapter} ou {verse}
if ($segments[0] === 'verses' && isset($segments[1]) && isset($segments[2]) && isset($segments[3])) {
    requireAuth($pdo);
    $version = strtolower(trim($segments[1]));
    $abbrev = strtolower(trim($segments[2]));
    if ($abbrev === 'atos') $abbrev = 'at';
    if ($abbrev === 'job') $abbrev = 'jó';
    $chapter = (int)$segments[3];

    // Buscar livro
    $stmtBook = $pdo->prepare("SELECT id, name, author, group_name, abbrev_pt, abbrev_en, total_chapters FROM bible_books WHERE abbrev_pt = ? OR name = ?");
    $stmtBook->execute([$abbrev, $abbrev]);
    $book = $stmtBook->fetch();

    if (!$book) {
        http_response_code(404);
        echo json_encode(['error' => 'Livro não encontrado'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // Se tiver 5º segmento, é versículo específico
    if (isset($segments[4])) {
        $verseNum = (int)$segments[4];
        $stmtVerse = $pdo->prepare("SELECT verse, text FROM bible_verses WHERE version_id = ? AND book_id = ? AND chapter = ? AND verse = ?");
        $stmtVerse->execute([$version, $book['id'], $chapter, $verseNum]);
        $verse = $stmtVerse->fetch();

        if (!$verse) {
            http_response_code(404);
            echo json_encode(['error' => 'Versículo não encontrado'], JSON_UNESCAPED_UNICODE);
            exit;
        }

        echo json_encode([
            'book' => [
                'name' => $book['name'],
                'author' => $book['author'],
                'group' => $book['group_name'],
                'abbrev' => ['pt' => $book['abbrev_pt'], 'en' => $book['abbrev_en']],
                'version' => strtoupper($version)
            ],
            'chapter' => $chapter,
            'number' => (int)$verse['verse'],
            'text' => $verse['text']
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // Capítulo Completo
    $stmtVerses = $pdo->prepare("SELECT verse, text FROM bible_verses WHERE version_id = ? AND book_id = ? AND chapter = ? ORDER BY verse ASC");
    $stmtVerses->execute([$version, $book['id'], $chapter]);
    $verses = $stmtVerses->fetchAll();

    $formattedVerses = array_map(function ($v) {
        return [
            'number' => (int)$v['verse'],
            'text' => $v['text']
        ];
    }, $verses);

    echo json_encode([
        'book' => [
            'name' => $book['name'],
            'author' => $book['author'],
            'group' => $book['group_name'],
            'version' => strtoupper($version),
            'abbrev' => ['pt' => $book['abbrev_pt'], 'en' => $book['abbrev_en']]
        ],
        'chapter' => [
            'number' => $chapter,
            'verses' => count($formattedVerses)
        ],
        'verses' => $formattedVerses
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// 404 para rotas não mapeadas
http_response_code(404);
echo json_encode(['error' => 'Endpoint não encontrado'], JSON_UNESCAPED_UNICODE);
