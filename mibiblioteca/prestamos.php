<?php
include($_SERVER['DOCUMENT_ROOT'] . '/mibiblioteca/include/db.php');

// Obtener todos los préstamos
$stmt = $pdo->query("SELECT * FROM Prestamos");
$prestamos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>CRUD Libros</title>
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
    <div class="container mt-4">
        <h2>Lista de Préstamos</h2>
        <a href="prestamos/agregar.php" class="btn btn-success mb-3">Crear Préstamo</a>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ID Usuario</th>
                    <th>ID Libro</th>
                    <th>Fecha de Préstamo</th>
                    <th>Fecha de Devolución</th>
                    <th>Estado</ <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($prestamos as $prestamo): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($prestamo['prestamo_id']); ?></td>
                        <td><?php echo htmlspecialchars($prestamo['usuario_id']); ?></td>
                        <td><?php echo htmlspecialchars($prestamo['libro_id']); ?></td>
                        <td><?php echo htmlspecialchars($prestamo['fecha_prestamo']); ?></td>
                        <td><?php echo htmlspecialchars($prestamo['fecha_devolucion']); ?></td>
                        <td><?php echo htmlspecialchars($prestamo['estado']); ?></td>
                        <td>
                            <a href="prestamos/editar.php?id=<?php echo $prestamo['prestamo_id']; ?>" class="btn btn-warning">Editar</a>
                            <a href="prestamos/eliminar.php?id=<?php echo $prestamo['prestamo_id']; ?>" class="btn btn-danger">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
<script>
