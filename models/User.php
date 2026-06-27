<?php

declare(strict_types=1);

use Database;

require_once __DIR__ . '/../config/Database.php';

class User {
    private ?PDO $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public static function consultarDni(string $dni): ?array {
        $params = json_encode(['dni' => $dni]);
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://apiperu.dev/api/dni",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_POSTFIELDS => $params,        
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'Content-Type: application/json',
                'Authorization: Bearer ' . API_TOKEN
            ],        
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            return null;
        }

        $data = json_decode($response, true);
        return ($data['success'] ?? false) ? $data['data'] : null;
    }

    public function login(string $username, string $password): ?array {
        $query = "SELECT * FROM users WHERE username_user = :username LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_user'])) {
            return $user;
        }
        return null;
    }

    public function registrar(array $datos): bool {
        $query = "INSERT INTO users (dni_user, name_user, father_surname_user, mother_surname_user, username_user, password_user, rol_user) 
                  VALUES (:dni, :name, :father, :mother, :username, :password, :rol)";
        
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':dni'      => $datos['dni'],
            ':name'     => $datos['name'],
            ':father'   => $datos['father'],
            ':mother'   => $datos['mother'],
            ':username' => $datos['username'],
            ':password' => password_hash($datos['password'], PASSWORD_BCRYPT),
            ':rol'      => $datos['rol'] ?? 2
        ]);
    }

    public function actualizarUsername(int $id, string $nuevoUsername): bool {
        $query = "UPDATE users SET username_user = :username WHERE id_user = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([':username' => $nuevoUsername, ':id' => $id]);
    }
}