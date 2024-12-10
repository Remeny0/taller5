<?php
include($_SERVER['DOCUMENT_ROOT'] . '/mibiblioteca/include/db.php');

// Obtener todos los registros de la tabla historial_lectura
try {
    $stmt = $pdo->query("SELECT * FROM historial_lectura");
    $historial = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo "Error al obtener el historial: " . $e->getMessage();
    $historial = []; // Inicializa como un array vacío para evitar el error en el foreach
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Historial de Lectura</title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">CRUD Usuarios</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Libros</a></li>
                <li class="nav-item"><a class="nav-link" href="usuarios.php">Usuarios</a></li>
                <li class="nav-item"><a class="nav-link" href="prestamos.php">Préstamos</a></li>
                <li class="nav-item"><a class="nav-link" href="historial.php">Historial de Lectura</a></li>
                <li class="nav-item"><a class="nav-link" href="recomendaciones.php">Recomendaciones</a></li>
            </ul>
        </div>
    </nav>
<body>
    <div class="container mt-4">
        <h2>Historial de Lectura</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ID Usuario</th>
                    <th>ID Libro</th>
                    <th>Fecha de Lectura</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($historial)): ?>
                    <?php foreach ($historial as $item): ?>
                    <tr>
                        <td><?php echo $item['historial_id']; ?></td>
                        <td><?php echo $item['usuario_id']; ?></td>
                        <td><?php echo $item['libro_id']; ?></td>
                        <td><?php echo $item['fecha_lectura']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No hay registros en el historial de lectura.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>