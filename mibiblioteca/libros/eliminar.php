<?php
include($_SERVER['DOCUMENT_ROOT'] . '/mibiblioteca/include/db.php');

// Inicializar variables
$id = $_GET['id'] ?? null;
$libro = null;
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
        // Procesar la eliminación
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Eliminar el libro de la base de datos
            $stmt = $pdo->prepare("DELETE FROM Libros WHERE libro_id = ?");
            if ($stmt->execute([$id])) {
                header("Location: ../index.php?mensaje=Libro eliminado exitosamente");
                exit();
            } else {
                $mensaje = "Error al eliminar el libro. Inténtalo de nuevo.";
            }
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
    <title>Eliminar Libro</title>
</head>
<body>
    <div class="container mt-4">
        <h2>Eliminar Libro</h2>
        <?php if ($mensaje): ?>
            <div class="alert alert-danger"><?php echo $mensaje; ?></div>
        <?php endif; ?>
        
        <?php if ($libro): ?>
            <p>¿Estás seguro de que deseas eliminar el libro "<strong><?php echo htmlspecialchars($libro['titulo']); ?></strong>" con ID <strong><?php echo htmlspecialchars($libro['libro_id']); ?></strong>?</p>
            <form method="POST">
                <button type="submit" class="btn btn-danger">Eliminar</button>
                <a href="../index.php" class="btn btn-secondary">Cancelar</a>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>