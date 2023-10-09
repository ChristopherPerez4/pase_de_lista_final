<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rfid_db";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener los parámetros de la solicitud AJAX
$id_asistencia_materia = $_GET['id_asistencia_materia'];
$asistencia = $_GET['asistencia'];

// Actualizar la asistencia en la base de datos
$sql = "UPDATE asistencia_materias SET asistio = '$asistencia' WHERE id_asistencia_materia = $id_asistencia_materia";

if ($conn->query($sql) === TRUE) {
    $response = array('mensaje' => 'Asistencia actualizada con éxito');
} else {
    $response = array('error' => 'Error al actualizar la asistencia: ' . $conn->error);
}

// Cerrar la conexión a la base de datos
$conn->close();

// Devolver la respuesta como un arreglo JSON
echo json_encode($response);
?>
