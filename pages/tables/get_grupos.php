<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$database = "rfid_db";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta SQL para obtener todos los grupos
$sql = "SELECT grupo_id, nombre_grupo FROM grupos";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $grupos = array();
    while ($row = $result->fetch_assoc()) {
        $grupos[] = $row;
    }
    echo json_encode($grupos);
} else {
    echo "No se encontraron grupos.";
}

$conn->close();
?>