<?php
/**
 * Bíblia Digital API Oficial (PHP + MySQL)
 * RESTful API para consulta da Bíblia Sagrada (NVI, ACF, AA)
 * 100% compatível com o formato da antiga abibliadigital
 */

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Configuração do Banco de Dados
$dbConfig = [
    'host' => '127.0.0.1', // ou 153.75.245.98 se remoto
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
    echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

// Roteador Simples
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Normalizar rota removendo prefixo /api se houver
$uri = preg_replace('#^/api#', '', $uri);
$segments = array_values(array_filter(explode('/', $uri)));

// Rota Raiz: Informações da API
if (empty($segments)) {
    echo json_encode([
        'name' => 'Bíblia Digital API Oficial',
        'version' => '1.0.0',
        'status' => 'online',
        'endpoints' => [
            'GET /api/versions' => 'Lista as versões disponíveis (NVI, ACF, AA)',
            'GET /api/books' => 'Lista todos os 66 livros da Bíblia',
            'GET /api/books/{abbrev}' => 'Retorna detalhes de um livro',
            'GET /api/verses/{version}/{abbrev}/{chapter}' => 'Retorna todos os versículos de um capítulo',
            'GET /api/verses/{version}/{abbrev}/{chapter}/{verse}' => 'Retorna um versículo específico',
            'GET /api/verses/{version}/random' => 'Retorna um versículo aleatório',
            'POST /api/verses/search' => 'Busca versículos por termo/palavra-chave'
        ]
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

// 1. GET /api/versions
if ($segments[0] === 'versions' && $method === 'GET') {
    $stmt = $pdo->query("SELECT id, name, description, language FROM bible_versions ORDER BY id");
    echo json_encode($stmt->fetchAll(), JSON_UNESCAPED_UNICODE);
    exit;
}

// 2. GET /api/books ou GET /api/books/{abbrev}
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
            echo json_encode(['error' => 'Livro não encontrado']);
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

// 3. GET /api/verses/{version}/random
if ($segments[0] === 'verses' && isset($segments[1]) && isset($segments[2]) && $segments[2] === 'random') {
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
        echo json_encode(['error' => 'Nenhum versículo encontrado']);
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

// 4. POST /api/verses/search ou GET /api/verses/search?version=nvi&search=termo
if ($segments[0] === 'verses' && isset($segments[1]) && $segments[1] === 'search') {
    $version = 'nvi';
    $search = '';

    if ($method === 'POST') {
        $body = json_decode(file_get_contents('php://input'), true);
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

// 5. GET /api/verses/{version}/{abbrev}/{chapter} ou {verse}
if ($segments[0] === 'verses' && isset($segments[1]) && isset($segments[2]) && isset($segments[3])) {
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
        echo json_encode(['error' => 'Livro não encontrado']);
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
            echo json_encode(['error' => 'Versículo não encontrado']);
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
echo json_encode(['error' => 'Endpoint não encontrado']);
