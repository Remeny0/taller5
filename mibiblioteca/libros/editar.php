<?php
include($_SERVER['DOCUMENT_ROOT'] . '/mibiblioteca/include/db.php');

// Inicializar variables
$id = $_GET['id'] ?? null;
$titulo = '';
$autor = '';
$genero = '';
$fecha_publicacion = '';
$disponible = '';
$mensaje = '';

// Verificar si se ha proporcionado un ID
if ($id) {
    // Obtener el libro de la base de datos
    $stmt = $pdo->prepare("SELECT * FROM Libros WHERE libro_id = ?");
    $stmt->execute([$id]);
    $libro = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar si el libro existe
    if (!$libro) {
        $mensaje = "Libro no encontrado.";
    } else {
        // Procesar el formulario al enviar
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $titulo = $_POST['titulo'];
            $autor = $_POST['autor'];
            $genero = $_POST['genero'];
            $fecha_publicacion = $_POST['fecha_publicacion'];
            $disponible = $_POST['disponible'];

            // Validar los datos
            if (empty($titulo) || empty($autor) || empty($genero) || empty($fecha_publicacion)) {
                $mensaje = "Todos los campos son obligatorios.";
            } else {
                // Actualizar el libro en la base de datos
                $stmt = $pdo->prepare("UPDATE Libros SET titulo = ?, autor = ?, genero = ?, fecha_publicacion = ?, disponible = ? WHERE libro_id = ?");
                if ($stmt->execute([$titulo, $autor, $genero, $fecha_publicacion, $disponible, $id])) {
                    header("Location: ../index.php?mensaje=Libro actualizado exitosamente");
                    exit();
                } else {
                    $mensaje = "Error al actualizar el libro. Inténtalo de nuevo.";
                }
            }
        } else {
            // Si no se envió el formulario, cargar los datos del libro
            $titulo = $libro['titulo'];
            $autor = $libro['autor'];
            $genero = $libro['genero'];
            $fecha_publicacion = $libro['fecha_publicacion'];
            $disponible = $libro['disponible'];
        }
    }
} else {
    $mensaje = "ID de libro no especificado.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Editar Libro</title>
</head>
<body>
    <div class="container mt-4">
        <h2>Editar Libro</h2>
        <?php if ($mensaje): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>
        <form action="" method="POST">
            <div class="form-group">
                <label for="titulo">Título</label>
                <input type="text" class="form-control" id="titulo" name="titulo" value="<?php echo htmlspecialchars($titulo); ?>" required>
            </div>
            <div class="form-group">
                <label for="autor">Autor</label>
                <input type="text" class="form-control" id="autor" name="autor" value="<?php echo htmlspecialchars($autor); ?>" required>
            </div>
            <div class="form-group">
                <label for="genero">Género</label>
                <input type="text" class="form-control" id="genero" name="genero" value="<?php echo htmlspecialchars($genero); ?>" required>
            </div>
            <div class="form-group">
                <label for="fecha_publicacion">Fecha de Publicación</label>
                <input type="date" class="form-control" id="fecha_publicacion" name="fecha_publicacion" value="<?php echo htmlspecialchars($fecha_publicacion); ?>" required>
            </div>
            <div class="form-group">
                <label for="disponible">Disponibles</label>
                <input type="number" class="form-control" id="disponible" name="disponible" value="<?php echo htmlspecialchars($disponible); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar Libro </button>
            <a href="../index.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>