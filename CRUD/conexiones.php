<?php
 /* Creamos una función llamada conectar */
function conectar(){
    /* Información del servidor*/

    $host="localhost";
    $user="root";
    $pass="1234";

    /* Base de datos */
    $db="aw221";

    $con=mysqli_connect($host,$user,$pass);

    mysqli_select_db($con,$db);

    return $con;
}
?>