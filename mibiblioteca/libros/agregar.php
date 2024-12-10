<?php
include($_SERVER['DOCUMENT_ROOT'] . '/mibiblioteca/include/db.php');

// Inicializar variables
$titulo = '';
$autor = '';
$genero = '';
$fecha_publicacion = '';
$disponible = '';
$mensaje = '';

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
        // Insertar el nuevo libro en la base de datos
        $stmt = $pdo->prepare("INSERT INTO Libros (titulo, autor, genero, fecha_publicacion, disponible) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$titulo, $autor, $genero, $fecha_publicacion, $disponible])) {
            header("Location: ../index.php?mensaje=Libro agregado exitosamente");
            exit();
        } else {
            $mensaje = "Error al agregar el libro. Inténtalo de nuevo.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Agregar Libro</title>
</head>
<body>
    <div class="container mt-4">
        <h2>Agregar Nuevo Libro</h2>
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
            <button type="submit" class="btn btn-primary">Agregar Libro</button>
            <a href="../index.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>