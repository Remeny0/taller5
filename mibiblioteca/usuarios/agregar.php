<?php
include($_SERVER['DOCUMENT_ROOT'] . '/mibiblioteca/include/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $fecha_registro = date('Y-m-d');
    $tipo_suscripcion = $_POST['tipo_suscripcion']; 

    // Preparar la declaración SQL para insertar el nuevo usuario
    $stmt = $pdo->prepare("INSERT INTO Usuarios (nombre, email, fecha_registro, tipo_suscripcion) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nombre, $email, $fecha_registro, $tipo_suscripcion]);

    // Redirigir a la lista de usuarios después de agregar
    header("Location: ../usuarios.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Agregar Usuario</title>
</head>
<body>
    <div class="container mt-4">
        <h2>Agregar Nuevo Usuario</h2>
        <form method="POST">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="tipo_suscripcion">Tipo de Suscripción</label>
                <select class="form-control" id="tipo_suscripcion" name="tipo_suscripcion" required>
                    <option value="Basico">Básico</option>
                    <option value="Premium">Premium</option>
                    <option value="VIP">VIP</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Agregar Usuario</button>
            <a href="../usuarios.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>