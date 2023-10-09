<?php
// Datos de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rfid_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Consulta SQL para obtener la lista de materias disponibles
$sql_materias = "SELECT materia_id, nombre_materia FROM materias";
$result_materias = $conn->query($sql_materias);

$materias = array();
if ($result_materias->num_rows > 0) {
    while ($row = $result_materias->fetch_assoc()) {
        $materias[] = $row;
    }
}

echo json_encode($materias);

$conn->close();
?>
