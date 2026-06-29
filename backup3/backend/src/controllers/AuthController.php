<?php

// ===== CORS (FIX CHUẨN) =====
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';

$allowed = [
  'http://localhost:45000',
  'http://127.0.0.1:45000'
];

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  exit;
}

// ===== ERROR HANDLING =====
ini_set('display_errors', '0');
error_reporting(E_ALL);

set_exception_handler(function ($e) {
  http_response_code(500);
  echo json_encode([
    "ok" => false,
    "error" => $e->getMessage()
  ]);
  exit;
});

set_error_handler(function ($no, $str) {
  http_response_code(500);
  echo json_encode([
    "ok" => false,
    "error" => $str
  ]);
  exit;
});

// ===== SESSION =====
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// ===== BOOTSTRAP =====
require_once __DIR__ . '/../../config/bootstrap.php';
require_once ROOT_PATH . '/config/Database.php';

// ===== HELPERS =====
function read_json()
{
  $raw = file_get_contents('php://input');
  $data = json_decode($raw, true);
  return is_array($data) ? $data : [];
}

function ok($data)
{
  echo json_encode($data);
  exit;
}

// ===== ACTION =====
$action = $_GET['action'] ?? '';

switch ($action) {

  case 'me': {
      if (!isset($_SESSION['user'])) {
        http_response_code(401);
        ok(["ok" => false, "error" => "Unauthenticated"]);
      }

      ok($_SESSION['user']);
      break; // Fixed: Added break
  }

  case 'login': {
      if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        ok(["ok" => false, "error" => "Method not allowed"]);
      }

      $data = read_json();

      $username = trim($data['username'] ?? '');
      $password = $data['password'] ?? '';

      if ($username === '' || $password === '') {
        http_response_code(400);
        ok(["ok" => false, "error" => "Missing credentials"]);
      }

      $db = (new Database())->getConnection();

      $sql = "SELECT a.account_id, a.username, a.password_hash, a.role, s.full_name
            FROM account a
            LEFT JOIN staff s ON s.account_id = a.account_id
            WHERE a.username = :u
            LIMIT 1";

      $st = $db->prepare($sql);
      $st->execute([':u' => $username]);
      $u = $st->fetch(PDO::FETCH_ASSOC);

      if (!$u || md5($password) !== $u['password_hash']) {
        http_response_code(401);
        ok(["ok" => false, "error" => "Invalid login"]);
      }

      $_SESSION['user'] = [
        "account_id" => (int)$u["account_id"],
        "username"   => $u["username"],
        "role"       => $u["role"],
        "full_name"  => $u["full_name"] ?: $u["username"]
      ];

      ok($_SESSION['user']);
      break; // Fixed: Added break
  }

  case 'logout': {
      $_SESSION = [];

      if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
          session_name(),
          '',
          time() - 42000,
          $params["path"],
          $params["domain"],
          $params["secure"],
          $params["httponly"]
        );
      }

      session_destroy();

      ok(["ok" => true]);
      break; // Fixed: Added break
  }

  default: {
      http_response_code(400);
      ok(["ok" => false, "error" => "Unknown action"]);
  }
}
