<?php
include($_SERVER['DOCUMENT_ROOT'] . '/mibiblioteca/include/db.php');

$id = $_GET['id'] ?? null; // Obtener el ID del usuario de la URL

if ($id) {
    // Preparar la consulta para obtener el usuario
    $stmt = $pdo->prepare("SELECT * FROM Usuarios WHERE usuario_id = ?");
    $stmt->execute([$id]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar si el usuario existe
    if (!$usuario) {
        die("Usuario no encontrado.");
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nombre = $_POST['nombre'];
        $email = $_POST['email'];
        $tipo_suscripcion = $_POST['tipo_suscripcion'];

        // Preparar la declaración SQL para actualizar el usuario
        $stmt = $pdo->prepare("UPDATE Usuarios SET nombre = ?, email = ?, tipo_suscripcion = ? WHERE usuario_id = ?");
        $stmt->execute([$nombre, $email, $tipo_suscripcion, $id]);

        // Redirigir a la lista de usuarios después de actualizar
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
    <title>Editar Usuario</title>
</head>
<body>
    <div class="container mt-4">
        <h2>Editar Usuario</h2>
        <form method="POST">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="tipo_suscripcion">Tipo de Suscripción</label>
                <select class="form-control" id="tipo_suscripcion" name="tipo_suscripcion" required>
                    <option value="Basico" <?php echo $usuario['tipo_suscripcion'] == 'Basico' ? 'selected' : ''; ?>>Básico</option>
                    <option value="Premium" <?php echo $usuario['tipo_suscripcion'] == 'Premium' ? 'selected' : ''; ?>>Premium</option>
                    <option value="VIP" <?php echo $usuario['tipo_suscripcion'] == 'VIP' ? 'selected' : ''; ?>>VIP</option>
                </select>
            </div>
            <button type="submit" class="btn btn-warning">Actualizar Usuario</button>
            <a href="../usuarios.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>