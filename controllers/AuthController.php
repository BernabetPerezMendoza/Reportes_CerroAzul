<?php

declare(strict_types=1);



require_once __DIR__ . '/../models/User.php';

class AuthController {
    public function login(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');

            $userModel = new User();
            $user = $userModel->login($username, $password);

            if ($user) {
                $_SESSION['user_id'] = $user['id_user'];
                $_SESSION['username'] = $user['username_user'];
                $_SESSION['rol'] = (int)$user['rol_user'];
                $_SESSION['nombre'] = $user['name_user'] . ' ' . $user['father_surname_user'];

                if ($_SESSION['rol'] === 1) {
                    header("Location: index.php?action=admin_dashboard");
                } else {
                    header("Location: index.php?action=ciudadano_dashboard");
                }
                exit;
            } else {
                $_SESSION['error'] = "Credenciales incorrectas.";
            }
        }
        header("Location: index.php?action=login");
    }

    public function registrar(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dni = trim($_POST['dni'] ?? '');
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');

            // Consumir API Perú para validación de identidad real
            $datosDni = User::consultarDni($dni);

            if (!$datosDni) {
                $_SESSION['error'] = "DNI no válido o error al consultar el proveedor oficial.";
                header("Location: index.php?action=register");
                exit;
            }

            $userModel = new User();
            $registroExitoso = $userModel->registrar([
                'dni' => $dni,
                'name' => $datosDni['nombres'],
                'father' => $datosDni['apellido_paterno'],
                'mother' => $datosDni['apellido_materno'],
                'username' => $username,
                'password' => $password,
                'rol' => 2
            ]);

            if ($registroExitoso) {
                $_SESSION['success'] = "Registro exitoso. ¡Inicia sesión!";
                header("Location: index.php?action=login");
            } else {
                $_SESSION['error'] = "El usuario o DNI ya se encuentra registrado.";
                header("Location: index.php?action=register");
            }
            exit;
        }
    }

    public function logout(): void {
        session_destroy();
        header("Location: index.php?action=login");
        exit;
    }
}