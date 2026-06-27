<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config/Database.php';

$database = new Database();
$db = $database->getConnection();

if (!$db) {
    die("Lo siento, no hay conexión activa a la base de datos.");
}

$dni = "12345678";
$name = "Carlos Edidson";
$father_surname = "Sanchez";
$mother_surname = "Alcala";
$username = "admin";
$password_clara = "admin123";

$password_hashed = password_hash($password_clara, PASSWORD_BCRYPT);

try {
    // 1. Limpiamos cualquier residuo previo con el mismo username o DNI
    $delete = $db->prepare("DELETE FROM users WHERE username_user = :username OR dni_user = :dni");
    $delete->execute([':username' => $username, ':dni' => $dni]);

    // 2. Insertamos el usuario con el hash nativo recién calculado
    $query = "INSERT INTO users (dni_user, name_user, father_surname_user, mother_surname_user, username_user, password_user, rol_user) 
              VALUES (:dni, :name, :father, :mother, :username, :password, 1)";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(':dni', $dni);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':father', $father_surname);
    $stmt->bindParam(':mother', $mother_surname);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password_hashed);

    if ($stmt->execute()) {
        echo "<div style='font-family:sans-serif; padding:20px; background:#e6fffa; color:#006d5b; border:1px solid #b2f5ea; border-radius:8px; max-width:500px; margin:40px auto;'>";
        echo "<h3 style='margin-top:0;'>¡Administrador configurado con éxito!</h3>";
        echo "<p>El servidor generó el hash correctamente.</p>";
        echo "<ul>";
        echo "<li><strong>Usuario:</strong> " . htmlspecialchars($username) . "</li>";
        echo "<li><strong>Contraseña:</strong> " . htmlspecialchars($password_clara) . "</li>";
        echo "</ul>";
        echo "<p style='font-size:12px; color:#4a5568;'>Recuerda borrar este archivo por seguridad de tu hosting.</p>";
        echo "</div>";
    }
} catch (PDOException $e) {
    echo "Hubo un error al insertar: " . $e->getMessage();
}