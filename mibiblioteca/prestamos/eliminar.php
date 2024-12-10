<?php
include($_SERVER['DOCUMENT_ROOT'] . '/mibiblioteca/include/db.php');

$id = $_GET['id'] ?? null;

if ($id) {
    // Verificar si el préstamo existe
    $stmt = $pdo->prepare("SELECT * FROM Prestamos WHERE prestamo_id = ?");
    $stmt->execute([$id]);
    $prestamo = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$prestamo) {
        die("Préstamo no encontrado.");
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Eliminar el préstamo
        $stmt = $pdo->prepare("DELETE FROM Prestamos WHERE prestamo_id = ?");
        $stmt->execute([$id]);

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
    <title>Eliminar Préstamo</title>
</head>
<body>
    <div class="container mt-4">
        <h2>Eliminar Préstamo</h2>
        <p>¿Estás seguro de que deseas eliminar el préstamo del libro con ID <strong><?php echo htmlspecialchars($prestamo['libro_id']); ?></strong>?</p>
        <form method="POST">
            <button type="submit" class="btn btn-danger">Eliminar</button>
            <a href="../prestamos.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>
