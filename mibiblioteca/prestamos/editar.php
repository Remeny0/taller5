<?php
include($_SERVER['DOCUMENT_ROOT'] . '/mibiblioteca/include/db.php');

$id = $_GET['id'] ?? null;

if ($id) {
    // Obtener el préstamo existente
    $stmt = $pdo->prepare("SELECT * FROM Prestamos WHERE prestamo_id = ?");
    $stmt->execute([$id]);
    $prestamo = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$prestamo) {
        die("Préstamo no encontrado.");
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $usuario_id = $_POST['usuario_id'];
        $libro_id = $_POST['libro_id'];
        $fecha_prestamo = $_POST['fecha_prestamo'];
        $fecha_devolucion = $_POST['fecha_devolucion'];
        $estado = $_POST['estado'];

        // Actualizar el préstamo
        $stmt = $pdo->prepare("UPDATE Prestamos SET usuario_id = ?, libro_id = ?, fecha_prestamo = ?, fecha_devolucion = ?, estado = ? WHERE prestamo_id = ?");
        $stmt->execute([$usuario_id, $libro_id, $fecha_prestamo, $fecha_devolucion, $estado, $id]);

        // Redirigir a la lista de préstamos
        header("Location: ../prestamos.php");
        exit();
    }
} else {
    die("ID de préstamo no proporcionado.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Editar Préstamo</title>
</head>
<body>
    <div class="container mt-4">
        <h2>Editar Préstamo</h2>
        <form method="POST">
            <div class="form-group">
                <label for="usuario_id">ID del Usuario</label>
                <input type="number" class="form-control" id="usuario_id" name="usuario_id" value="<?php echo htmlspecialchars($prestamo['usuario_id']); ?>" required>
            </div>
            <div class="form-group">
                <label for="libro_id">ID del Libro</label>
                <input type="number" class="form-control" id="libro_id" name="libro_id" value="<?php echo htmlspecialchars($prestamo['libro_id']); ?>" required>
            </div>
            <div class="form-group">
                <label for="fecha_prestamo">Fecha de Préstamo</label>
                <input type="date" class="form-control" id="fecha_prestamo" name="fecha_prestamo" value="<?php echo htmlspecialchars($prestamo['fecha_prestamo']); ?>" required>
            </div>
            <div class="form-group">
                <label for="fecha_devolucion">Fecha de Devolución</label>
                <input type="date" class="form-control" id="fecha_devolucion" name="fecha_devolucion" value="<?php echo htmlspecialchars($prestamo['fecha_devolucion']); ?>" required>
            </div>
            <div class="form-group">
                <label for="estado">Estado</label>
                <select class="form-control" id="estado" name="estado" required>
                    <option value=" Activo" <?php echo $prestamo['estado'] == 'Activo' ? 'selected' : ''; ?>>Activo</option>
                    <option value="Devuelto" <?php echo $prestamo['estado'] == 'Devuelto' ? 'selected' : ''; ?>>Devuelto</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar Préstamo</button>
            <a href="../prestamos.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>