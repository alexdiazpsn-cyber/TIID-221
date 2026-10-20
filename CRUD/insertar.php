<?php
/*Incluir la información del archivo.php*/
include("conexiones.php");

/*Mandamos llamar a la funcion de conectar para ejecutarla*/
$con = conectar();

$matricula = $_POST["matricula"] ?? null;
$nombre = $_POST["nombre"] ?? null;
$apellido_p = $_POST["apellido_p"] ?? null;
$apellido_m = $_POST["apellido_m"] ?? null;
$edad = filter_input(INPUT_POST, "edad", FILTER_VALIDATE_INT);

if (
    $_SERVER["REQUEST_METHOD"] !== "POST" ||
    !is_string($matricula) || trim($matricula) === "" ||
    !is_string($nombre) || trim($nombre) === "" ||
    !is_string($apellido_p) || trim($apellido_p) === "" ||
    !is_string($apellido_m) || trim($apellido_m) === "" ||
    $edad === false || $edad === null || $edad < 0
) {
    http_response_code(400);
    exit("Error: los datos del alumno no son válidos.");
}

$sql = "INSERT INTO alumnos (matricula, nombre, apellido_p, apellido_m, edad)
        VALUES (?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($con, $sql);

if (!$stmt) {
    http_response_code(500);
    exit("Error al preparar el registro del alumno.");
}

mysqli_stmt_bind_param(
    $stmt,
    "ssssi",
    $matricula,
    $nombre,
    $apellido_p,
    $apellido_m,
    $edad
);

if (!mysqli_stmt_execute($stmt)) {
    http_response_code(500);
    exit("Error al insertar al alumno.");
}

header("Location: alumnos.php");
exit;