<?php
include($_SERVER['DOCUMENT_ROOT'] . '/mibiblioteca/include/db.php');

// Obtener todos los registros de la tabla libros
try {
    $stmt = $pdo->query("SELECT * FROM libros ORDER BY rank DESC");
    $libros = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo "Error al obtener los libros: " . $e->getMessage();
    $libros = []; // Inicializa como un array vacío para evitar el error en el foreach
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Rank de Libros</title>
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
                <li class="nav-item"><a class="nav-link" href="rank_libros.php">Rank de Libros</a></li>
            </ul>
        </div>
    </nav>
<body>
    <div class="container mt-4">
        <h2>Rank de Libros</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID Libro</th>
                    <th>Título</th>
                    <th>Autor</th>
                    <th>Género</th>
                    <th>Rank</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($libros)): ?>
                    <?php foreach ($libros as $item): ?>
                    <tr>
                        <td><?php echo $item['libro_id']; ?></td>
                        <td><?php echo $item['titulo']; ?></td>
                        <td><?php echo $item['autor']; ?></td>
                        <td><?php echo $item['genero']; ?></td>
                        <td><?php echo $item['rank']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No hay libros disponibles.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>