<?php
/*Incluir la información del archivo.php*/
include("conexiones.php");

/*Mandamos llamar a la funcion de conectar para ejecutarla*/
$con = conectar();

/*Dame todo lo que tengas de alumnos*/
$sql = "SELECT * FROM alumnos";

/*Ejecutar la consulta y tendremos acceso a la conexion*/
$query = mysqli_query($con,$sql);

if (!$query) {
    exit("Error al consultar los alumnos.");
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumnos</title>
</head>
<body>
    <h1>Alumnos</h1>
    <table border="1">
        <thead>
        <tr>
            <th>ID</th>
            <th>Matrícula</th>
            <th>Nombre</th>
            <th>Apellido_Paterno</th>
            <th>Apellido_Materno</th>
            <th>Edad</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
                <?php if (mysqli_num_rows($query) === 0) { ?>
                    <tr>
                        <td colspan="7">No hay alumnos registrados. Agrega uno con el formulario para mostrar sus acciones.</td>
                    </tr>
                <?php } ?>
                <?php while ($alumno = mysqli_fetch_assoc($query)) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars((string) $alumno["id"], ENT_QUOTES, "UTF-8"); ?></td>
                        <td><?php echo htmlspecialchars((string) $alumno["matricula"], ENT_QUOTES, "UTF-8"); ?></td>
                        <td><?php echo htmlspecialchars((string) $alumno["nombre"], ENT_QUOTES, "UTF-8"); ?></td>
                        <td><?php echo htmlspecialchars((string) $alumno["apellido_p"], ENT_QUOTES, "UTF-8"); ?></td>
                        <td><?php echo htmlspecialchars((string) $alumno["apellido_m"], ENT_QUOTES, "UTF-8"); ?></td>
                        <td><?php echo htmlspecialchars((string) $alumno["edad"], ENT_QUOTES, "UTF-8"); ?></td>
                        <td>
                            <form action="editar.php" method="GET" style="display: inline;">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars((string) $alumno["id"], ENT_QUOTES, "UTF-8"); ?>">
                                <button type="submit">Editar</button>
                            </form>
                            <form action="delete.php" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar a este alumno?');">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars((string) $alumno["id"], ENT_QUOTES, "UTF-8"); ?>">
                                <button type="submit">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <h2>Formulario</h2>
        <form action="insertar.php" method="POST">
            <input type="text" class="form-control" name="matricula" placeholder="Matrícula">
            <input type="text" class="form-control" name="nombre" placeholder="Nombre(s)">
        <input type="text" class="form-control" name="apellido_p" placeholder="Apellido Paterno">
        <input type="text" class="form-control" name="apellido_m" placeholder="Apellido Materno">
        <input type="text" class="form-control" name="edad" placeholder="Edad">
        <button type="submit">Guardar</button>
    </form>
</body>
</html>