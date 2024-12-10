<?php
include($_SERVER['DOCUMENT_ROOT'] . '/mibiblioteca/include/db.php');

$id = $_GET['id'] ?? null; // Obtener el ID del usuario de la URL

if ($id) {
    // Preparar la consulta para verificar si el usuario existe
    $stmt = $pdo->prepare("SELECT * FROM Usuarios WHERE usuario_id = ?");
    $stmt->execute([$id]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar si el usuario existe
    if (!$usuario) {
        die("Usuario no encontrado.");
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Preparar la declaración SQL para eliminar el usuario
        $stmt = $pdo->prepare("DELETE FROM Usuarios WHERE usuario_id = ?");
        $stmt->execute([$id]);

        // Redirigir a la lista de usuarios después de eliminar
        header("Location: ../usuarios.php");
        exit();
    }
} else {
    die("ID de usuario no proporcionado.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Eliminar Usuario</title>
</head>
<body>
    <div class="container mt-4">
        <h2>Eliminar Usuario</h2>
        <p>¿Estás seguro de que deseas eliminar al usuario <strong><?php echo htmlspecialchars($usuario['nombre']); ?></strong>?</p>
        <form method="POST">
            <button type="submit" class="btn btn-danger">Eliminar</button>
            <a href="../usuarios.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>