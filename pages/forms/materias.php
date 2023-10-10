<?php
// Conexión a la base de datos (reemplaza con tus datos de conexión)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rfid_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Recibir datos del formulario
$nombre_materia = $_POST['nombre_materia'];
$hora_inicio = $_POST['hora_inicio'];
$hora_fin = $_POST['hora_fin'];

// Insertar la nueva materia en la base de datos
$sql = "INSERT INTO materias (nombre_materia, hora_inicio, hora_fin) VALUES ('$nombre_materia', '$hora_inicio', '$hora_fin')";

if ($conn->query($sql) === TRUE) {
    $response = array("success" => true, "message" => "Los datos se han guardado correctamente");
    echo json_encode($response);
} else {
    echo "Error al crear la materia: " . $conn->error;
}

$conn->close();
?>
