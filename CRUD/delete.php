<?php
/*Incluir la información del archivo.php*/
include("conexiones.php");

/*Mandamos llamar a la funcion de conectar para ejecutarla*/
$con = conectar();

$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

if ($id === false || $id === null || $id < 1) {
    http_response_code(400);
    exit("Error: el ID del alumno no es válido.");
}

$sql = "DELETE FROM alumnos WHERE id = ?";
$stmt = mysqli_prepare($con, $sql);

if (!$stmt) {
    http_response_code(500);
    exit("Error al preparar la eliminación del alumno.");
}

mysqli_stmt_bind_param($stmt, "i", $id);

if (!mysqli_stmt_execute($stmt)) {
    http_response_code(500);
    exit("Error al eliminar al alumno.");
}

if (mysqli_stmt_affected_rows($stmt) === 0) {
    http_response_code(404);
    exit("No se encontró un alumno con ese ID.");
}

header("Location: alumnos.php");
exit;
?>