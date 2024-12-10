<?php
include($_SERVER['DOCUMENT_ROOT'] . '/mibiblioteca/include/db.php');

// Obtener todos los registros de la tabla recomendaciones
try {
    $stmt = $pdo->query("SELECT * FROM recomendaciones");
    $recomendaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo "Error al obtener las recomendaciones: " . $e->getMessage();
    $recomendaciones = []; // Inicializa como un array vacío para evitar el error en el foreach
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Recomendaciones</title>
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
        <h2>Recomendaciones</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID Recomendación</th>
                    <th>ID Usuario</th>
                    <th>ID Libro</th>
                    <th>Fecha de Recomendación</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($recomendaciones)): ?>
                    <?php foreach ($recomendaciones as $item): ?>
                    <tr>
                        <td><?php echo $item['recomendacion_id']; ?></td>
                        <td><?php echo $item['usuario_id']; ?></td>
                        <td><?php echo $item['libro_id']; ?></td>
                        <td><?php echo $item['fecha_recomendacion']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No hay recomendaciones disponibles.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>