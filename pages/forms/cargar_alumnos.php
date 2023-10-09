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

// Consulta SQL para obtener la lista de alumnos disponibles
$sql_alumnos = "SELECT id_alumno, nombre FROM alumnos";
$result_alumnos = $conn->query($sql_alumnos);

$alumnos = array();
if ($result_alumnos->num_rows > 0) {
    while ($row = $result_alumnos->fetch_assoc()) {
        $alumnos[] = $row;
    }
}

echo json_encode($alumnos);

$conn->close();
?>
