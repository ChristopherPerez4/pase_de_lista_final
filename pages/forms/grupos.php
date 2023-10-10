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
$nombre_grupo = $_POST['nombre_grupo'];

// Insertar el nuevo grupo en la base de datos
$sql = "INSERT INTO grupos (nombre_grupo) VALUES ('$nombre_grupo')";

if ($conn->query($sql) === TRUE) {
    $response = array("success" => true, "message" => "Los datos se han guardado correctamente");
    echo json_encode($response);
} else {
    echo "Error al crear el grupo: " . $conn->error;
}

$conn->close();
?>
