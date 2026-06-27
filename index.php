<?php

declare(strict_types=1);

use AuthController;
use Report;

require_once __DIR__ . '/config/Config.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/models/Report.php';

$action = $_GET['action'] ?? 'login';

function verificarAutenticado(): void {
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php?action=login");
        exit;
    }
}

switch ($action) {
    case 'login':
        require_once __DIR__ . '/views/auth/login.php';
        break;

    case 'register':
        require_once __DIR__ . '/views/auth/register.php';
        break;
    case 'login_process':
        $auth = new AuthController();
        $auth->login();
        break;

    case 'register_process':
        $auth = new AuthController();
        $auth->registrar();
        break;

    case 'logout':
        $auth = new AuthController();
        $auth->logout();
        break;

    case 'ciudadano_dashboard':
        verificarAutenticado();
        $reportModel = new Report();
        $reportes = $reportModel->obtenerPorUsuario((int)$_SESSION['user_id']);
        require_once __DIR__ . '/views/ciudadano/dashboard.php';
        break;
    
    case 'admin_dashboard':
        verificarAutenticado();
        if ($_SESSION['rol'] !== 1) {
            die("Acceso denegado.");
        }
        $reportModel = new Report();
        $stats = $reportModel->obtenerEstadisticas();
        $reportes = $reportModel->obtenerTodos();
        require __DIR__ . '/views/admin/dashboard.php';
        break;

    case 'guardar_reporte':
        verificarAutenticado();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $descripcion = trim($_POST['descripcion'] ?? '');
            $userId = (int)$_SESSION['user_id'];
            
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
                $permitidos = ['jpg', 'jpeg', 'png', 'webp'];
                $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
                
                if (in_array($ext, $permitidos)) {
                    $folder = __DIR__ . '/public/uploads/';
                    if (!is_dir($folder)) {
                        mkdir($folder, 0777, true);
                    }
                    
                    $nuevoNombre = 'rep_' . uniqid() . '.' . $ext;
                    
                    if (move_uploaded_file($_FILES['foto']['tmp_name'], $folder . $nuevoNombre)) {
                        $reportModel = new Report();
                        $reportModel->crear($userId, $descripcion, $nuevoNombre);
                        $_SESSION['success'] = "¡Reporte registrado correctamente en Lunahuaná!";
                    } else {
                        $_SESSION['error'] = "Error al mover la imagen al servidor.";
                    }
                } else {
                    $_SESSION['error'] = "Formato de imagen no permitido (Solo JPG, PNG, WEBP).";
                }
            } else {
                $_SESSION['error'] = "Es obligatorio adjuntar una fotografía.";
            }
        }
        header("Location: index.php?action=ciudadano_dashboard");
        exit;

    case 'revisar_reporte':
        verificarAutenticado();
        if ($_SESSION['rol'] !== 1) die("Acceso denegado.");
        
        $idReporte = (int)($_GET['id'] ?? 0);
        if ($idReporte > 0) {
            $reportModel = new Report();
            $reportModel->cambiarEstado($idReporte, 'REVISADO');
            $_SESSION['success'] = "El reporte ha sido marcado como REVISADO.";
        }
        header("Location: index.php?action=admin_dashboard");
        exit;

    default:
        require_once __DIR__ . '/views/auth/login.php';
        break;
}