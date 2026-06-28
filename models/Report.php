<?php

declare(strict_types=1);

use Database;

require_once __DIR__ . '/../config/Database.php';

class Report {
    private ?PDO $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function crear(int $userId, string $descripcion, string $foto): bool {
        $query = "INSERT INTO reports (user_id, descripcion, foto) VALUES (:user_id, :descripcion, :foto)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':user_id' => $userId,
            ':descripcion' => $descripcion,
            ':foto' => $foto
        ]);
    }

    public function obtenerPorUsuario(int $userId): array {
        $query = "SELECT * FROM reports WHERE user_id = :user_id ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function obtenerTodos(): array {
        $query = "SELECT r.*, u.name_user, u.father_surname_user, u.mother_surname_user, u.username_user 
                  FROM reports r 
                  JOIN users u ON r.user_id = u.id_user 
                  ORDER BY r.created_at DESC";
        return $this->conn->query($query)->fetchAll();
    }

    public function cambiarEstado(int $id, string $estado): bool {
        $query = "UPDATE reports SET estado = :estado WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([':estado' => $estado, ':id' => $id]);
    }

    public function obtenerEstadisticas(): array {
        $stats = ['total' => 0, 'pendiente' => 0, 'revisado' => 0];
        
        $query = "SELECT estado, COUNT(*) as total FROM reports GROUP BY estado";
        $res = $this->conn->query($query)->fetchAll();
        
        foreach ($res as $row) {
            if ($row['estado'] === 'PENDIENTE') $stats['pendiente'] = (int)$row['total'];
            if ($row['estado'] === 'REVISADO') $stats['revisado'] = (int)$row['total'];
            $stats['total'] += (int)$row['total'];
        }
        return $stats;
    }
}