<?php
include($_SERVER['DOCUMENT_ROOT'] . '/mibiblioteca/include/db.php');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>CRUD Usuarios</title>
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
        <h2>Lista de Usuarios</h2>
        <a href="usuarios/agregar.php" class="btn btn-primary mb-3">Agregar Nuevo Usuario</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Fecha de Registro</th>
                    <th>Tipo de Suscripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $pdo->query("SELECT * FROM Usuarios");
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    // Verificar si el índice 'usuario_id' existe
                    if (isset($row['usuario_id'])) {
                        echo "<tr>
                            <td>{$row['usuario_id']}</td>
                            <td>{$row['nombre']}</td>
                            <td>{$row['email']}</td>
                            <td>{$row['fecha_registro']}</td>
                            <td>{$row['tipo_suscripcion']}</td>
                            <td>
                                <a href='usuarios/editar.php?id={$row['usuario_id']}' class='btn btn-warning'>Editar</a>
                                <a href='usuarios/eliminar.php?id={$row['usuario_id']}' class='btn btn-danger'>Eliminar</a>
                            </td>
                        </tr>";
                    } else {
                        echo "<tr>
                            <td colspan='6'>Error: ID de usuario no encontrado.</td>
                        </tr>";
                    }
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>