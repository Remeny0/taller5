<?php
include($_SERVER['DOCUMENT_ROOT'] . '/mibiblioteca/include/db.php');
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
        <h2>Lista de Libros</h2>
        <a href="libros/agregar.php" class="btn btn-primary mb-3">Agregar Nuevo Libro</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Autor</th>
                    <th>Género</th>
                    <th>Fecha Publicación</th>
                    <th>Disponibles</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                try {
                    $stmt = $pdo->query("SELECT * FROM Libros");
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        // Cambia 'id_libro' a 'libro_id'
                        if (isset($row['libro_id'])) {
                            echo "<tr>
                                <td>" . htmlspecialchars($row['libro_id']) . "</td>
                                <td>" . htmlspecialchars($row['titulo']) . "</td>
                                <td>" . htmlspecialchars($row['autor']) . "</td>
                                <td>" . htmlspecialchars($row['genero']) . "</td>
                                <td>" . htmlspecialchars($row['fecha_publicacion']) . "</td>
                                <td>" . htmlspecialchars($row['disponible']) . "</td>
                                <td>
                                    <a href='libros/editar.php?id=" . htmlspecialchars($row['libro_id']) . "' class='btn btn-warning'>Editar</a>
                                    <a href='libros/eliminar.php?id=" . htmlspecialchars($row['libro_id']) . "' class='btn btn-danger' onclick='return confirm(\"¿Estás seguro de que deseas eliminar este libro?\");'>Eliminar</a>
                                </td>
                            </tr>";
                        } else {
                            echo "<tr><td colspan='7' class='text-danger'>Error: ID de libro no disponible.</td></tr>";
                        }
                    }
                } catch (PDOException $e) {
                    echo "<tr><td colspan='7' class='text-danger'>Error al cargar los libros: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html