<?php

declare(strict_types=1);

use AuthController;
use Report;
use User;
use Database;

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

        require_once __DIR__ . '/models/User.php';
        $userModel = new User();
        $usuarios = $userModel->obtenerTodos();

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

    case 'admin_crear_usuario':
        verificarAutenticado();
        if ($_SESSION['rol'] !== 1) die("Acceso denegado.");
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dni = trim($_POST['dni'] ?? '');
            $nombres = trim($_POST['nombres'] ?? '');
            $paterno = trim($_POST['paterno'] ?? '');
            $materno = trim($_POST['materno'] ?? '');
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $rol = (int)($_POST['rol'] ?? 2);

            require_once __DIR__ . '/models/User.php';
            $userModel = new User();
            
            $exito = $userModel->registrar([
                'dni' => $dni,
                'name' => $nombres,
                'father' => $paterno,
                'mother' => $materno,
                'username' => $username,
                'password' => $password,
                'rol' => $rol
            ]);

            if ($exito) {
                $_SESSION['success'] = "Usuario ($nombres) creado correctamente.";
            } else {
                $_SESSION['error'] = "Error: El DNI o el Nombre de Usuario ya están registrados.";
            }
        }
        header("Location: index.php?action=admin_dashboard");
        exit;

    case 'consultar_dni_ajax':
        header('Content-Type: application/json');
        
        $dni = trim($_GET['dni'] ?? '');
        if (strlen($dni) === 8) {
            require_once __DIR__ . '/models/User.php';
            $datosDni = User::consultarDni($dni);
            
            if ($datosDni) {
                echo json_encode(['success' => true, 'data' => $datosDni]);
                exit;
            }
        }
        echo json_encode(['success' => false, 'message' => 'DNI no encontrado o no válido.']);
        exit;

    case 'verificar_username_ajax':
        header('Content-Type: application/json');
        
        $username = trim($_GET['username'] ?? '');
        if (strlen($username) > 0) {
            require_once __DIR__ . '/models/User.php';
            $userModel = new User();
            $existe = $userModel->existeUsername($username);
            echo json_encode(['existe' => $existe]);
            exit;
        }
        echo json_encode(['existe' => false]);
        exit;

    case 'verificar_dni_existente':
        header('Content-Type: application/json');
        
        $dni = trim($_GET['dni'] ?? '');
        if (strlen($dni) === 8) {
            require_once __DIR__ . '/models/User.php';
            $userModel = new User();
            $existe = $userModel->existeDni($dni);
            echo json_encode(['existe' => $existe]);
            exit;
        }
        echo json_encode(['existe' => false]);
        exit;

    case 'ciudadano_perfil':
        verificarAutenticado();
        
        require_once __DIR__ . '/models/User.php';
        $userModel = new User();

        $idSesion = (int)$_SESSION['user_id'];
        $usuario = $userModel->obtenerPorId($idSesion);

        if (!$usuario) {
            die("Error crítico: El sistema intentó buscar al usuario con el ID: " . $idSesion . ", pero la tabla 'users' dijo que no existe.");
        }

        require_once __DIR__ . '/views/ciudadano/perfil.php';
        break;

    case 'actualizar_perfil':
        verificarAutenticado();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = (int)$_SESSION['user_id'];
            $nuevoUsername = trim($_POST['username'] ?? '');
            $nuevaPassword = trim($_POST['password'] ?? '');

            require_once __DIR__ . '/models/User.php';
            $userModel = new User();

            $actualizado = false;

            if ($nuevoUsername !== $_SESSION['username']) {
                if ($userModel->existeUsername($nuevoUsername)) {
                    $_SESSION['error'] = "El nombre de usuario '$nuevoUsername' ya está en uso.";
                    header("Location: index.php?action=ciudadano_perfil");
                    exit;
                }
                $userModel->actualizarUsername($userId, $nuevoUsername);
                $_SESSION['username'] = $nuevoUsername;
                $actualizado = true;
            }

            if (!empty($nuevaPassword)) {
                $conn = (new Database())->getConnection();
                $hashedPassword = password_hash($nuevaPassword, PASSWORD_BCRYPT);
                $stmt = $conn->prepare("UPDATE users SET password_user = :password WHERE id_user = :id");
                $stmt->execute([':password' => $hashedPassword, ':id' => $userId]);
                $actualizado = true;
            }

            if ($actualizado) {
                $_SESSION['success'] = "Tus credenciales de acceso han sido actualizados correctamente.";
            } else {
                $_SESSION['info'] = "No se detectaron cambios para guardar.";
            }
        }
        header("Location: index.php?action=ciudadano_perfil");
        exit;

    case 'admin_perfil':
        verificarAutenticado();
        if ($_SESSION['rol'] !== 1) die("Acceso denegado.");

        require_once __DIR__ . '/models/User.php';
        $userModel = new User();
        $idSesion = (int)$_SESSION['user_id'];
        $usuario = $userModel->obtenerPorId($idSesion);

        if (!$usuario) {
            die("Error crítico: Usuario no encontrado en la base de datos.");
        }

        require_once __DIR__ . '/views/admin/perfil.php';
        break;

    case 'actualizar_perfil_admin':
        verificarAutenticado();
        if ($_SESSION['rol'] !== 1) die("Acceso denegado.");
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = (int)$_SESSION['user_id'];
            $nuevoUsername = trim($_POST['username'] ?? '');
            $nuevaPassword = trim($_POST['password'] ?? '');

            require_once __DIR__ . '/models/User.php';
            $userModel = new User();
            $actualizado = false;

            if ($nuevoUsername !== $_SESSION['username']) {
                if ($userModel->existeUsername($nuevoUsername)) {
                    $_SESSION['error'] = "El nombre de usuario '$nuevoUsername' ya está en uso.";
                    header("Location: index.php?action=admin_perfil");
                    exit;
                }
                $userModel->actualizarUsername($userId, $nuevoUsername);
                $_SESSION['username'] = $nuevoUsername; 
                $actualizado = true;
            }

            if (!empty($nuevaPassword)) {
                $db = (new Database())->getConnection();
                $hash = password_hash($nuevaPassword, PASSWORD_BCRYPT);
                $stmt = $db->prepare("UPDATE users SET password_user = :pass WHERE id_user = :id");
                $stmt->execute([':pass' => $hash, ':id' => $userId]);
                $actualizado = true;
            }

            if ($actualizado) {
                $_SESSION['success'] = "Tus credenciales de administrador han sido actualizadas.";
            } else {
                $_SESSION['error'] = "No se detectaron cambios para guardar.";
            }
        }
        header("Location: index.php?action=admin_perfil");
        exit;

    default:
        require_once __DIR__ . '/views/auth/login.php';
        break;
}