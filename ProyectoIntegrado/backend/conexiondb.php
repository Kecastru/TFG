<?php

//Definine las variables para la conexión a la base de datos
    $servername = 'localhost';
    $username = 'root';
    $password = 'root25';
    $dbname = "funkopop_collections";

// Crea una nueva conexión a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);

//Comprueba si hay errores en la conexión
if ($conn->connect_error) {
    die ("Error de conexión: ". $conn->connect_error);
}

?>