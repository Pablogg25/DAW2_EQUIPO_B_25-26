<?php
$servername = "98.92.82.12";
$username = "grupo3.1";
$password = "123456789";
$database = "la_cremallera";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
echo "Conectado a MySQL en EC2 desde proyecto local - mi pc fisica!";
?>

<<<<<<< HEAD
<!-- Gustavo -->
=======
<!-- Pablo Nunez -->
>>>>>>> 6cb647998885d445edd914e8ed73124795a8bac1
