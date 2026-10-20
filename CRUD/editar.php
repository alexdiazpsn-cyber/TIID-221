<?php
/*Incluir la información del archivo.php*/
include("conexiones.php");

/*Mandamos llamar a la funcion de conectar para ejecutarla*/
$con = conectar();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
    $matricula = $_POST["matricula"] ?? null;
    $nombre = $_POST["nombre"] ?? null;
    $apellido_p = $_POST["apellido_p"] ?? null;
    $apellido_m = $_POST["apellido_m"] ?? null;
    $edad = filter_input(INPUT_POST, "edad", FILTER_VALIDATE_INT);

    if (
        $id === false || $id === null || $id < 1 ||
        !is_string($matricula) || trim($matricula) === "" ||
        !is_string($nombre) || trim($nombre) === "" ||
        !is_string($apellido_p) || trim($apellido_p) === "" ||
        !is_string($apellido_m) || trim($apellido_m) === "" ||
        $edad === false || $edad === null || $edad < 0
    ) {
        http_response_code(400);
        exit("Error: los datos del alumno no son válidos.");
    }

    $sql = "UPDATE alumnos
            SET matricula = ?, nombre = ?, apellido_p = ?, apellido_m = ?, edad = ?
            WHERE id = ?";
    $stmt = mysqli_prepare($con, $sql);

    if (!$stmt) {
        http_response_code(500);
        exit("Error al preparar la actualización del alumno.");
    }

    mysqli_stmt_bind_param(
        $stmt,
        "ssssii",
        $matricula,
        $nombre,
        $apellido_p,
        $apellido_m,
        $edad,
        $id
    );

    if (!mysqli_stmt_execute($stmt)) {
        http_response_code(500);
        exit("Error al actualizar al alumno.");
    }

    header("Location: alumnos.php");
    exit;
}

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if ($id === false || $id === null || $id < 1) {
    http_response_code(400);
    exit("Error: el ID del alumno no es válido.");
}

$sql = "SELECT id, matricula, nombre, apellido_p, apellido_m, edad
        FROM alumnos WHERE id = ?";
$stmt = mysqli_prepare($con, $sql);

if (!$stmt) {
    http_response_code(500);
    exit("Error al preparar la consulta del alumno.");
}

mysqli_stmt_bind_param($stmt, "i", $id);

if (!mysqli_stmt_execute($stmt)) {
    http_response_code(500);
    exit("Error al consultar al alumno.");
}

$resultado = mysqli_stmt_get_result($stmt);
$alumno = mysqli_fetch_assoc($resultado);

if (!$alumno) {
    http_response_code(404);
    exit("No se encontró un alumno con ese ID.");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar alumno</title>
</head>
<body>
    <h1>Editar alumno</h1>
    <form action="editar.php" method="POST">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars((string) $alumno["id"], ENT_QUOTES, "UTF-8"); ?>">
        <input type="text" name="matricula" placeholder="Matrícula" value="<?php echo htmlspecialchars((string) $alumno["matricula"], ENT_QUOTES, "UTF-8"); ?>" required>
        <input type="text" name="nombre" placeholder="Nombre(s)" value="<?php echo htmlspecialchars((string) $alumno["nombre"], ENT_QUOTES, "UTF-8"); ?>" required>
        <input type="text" name="apellido_p" placeholder="Apellido Paterno" value="<?php echo htmlspecialchars((string) $alumno["apellido_p"], ENT_QUOTES, "UTF-8"); ?>" required>
        <input type="text" name="apellido_m" placeholder="Apellido Materno" value="<?php echo htmlspecialchars((string) $alumno["apellido_m"], ENT_QUOTES, "UTF-8"); ?>" required>
        <input type="number" name="edad" placeholder="Edad" min="0" value="<?php echo htmlspecialchars((string) $alumno["edad"], ENT_QUOTES, "UTF-8"); ?>" required>
        <button type="submit">Guardar cambios</button>
    </form>
    <a href="alumnos.php">Cancelar</a>
</body>
</html>
