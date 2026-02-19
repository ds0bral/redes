<?php
session_start();

// Configurações da Base de Dados
$host = 'localhost';
$dbname = 'qualiauto';
$user = 'root'; 
$pass = ''; 

// Conexão PDO
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro de ligação à base de dados: " . $e->getMessage());
}

// Token de Segurança para Formulários (CSRF)
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Função de Proteção de Páginas
function verificar_autenticacao() {
    if (!isset($_SESSION['sessao_ativa'])) {
        header("Location: login.php");
        exit();
    }
}

// Função para verificar se é admin
function verificar_admin() {
    if (!isset($_SESSION['sessao_ativa']) || $_SESSION['perfil'] !== 'admin') {
        header("Location: index.php");
        exit();
    }
}
?>