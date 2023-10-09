<?php
// Conexión a la base de datos (reemplaza estos valores con los tuyos)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rfid_db";

// Conexión a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el ID del alumno y la materia desde la solicitud GET
$alumno_id = $_GET['alumno_id'];
$materia_id = $_GET['materia_id'];

// Consulta para obtener el nombre del alumno
$sql_nombre = "SELECT nombre FROM alumnos WHERE id_alumno = $alumno_id";
$result_nombre = $conn->query($sql_nombre);

if ($result_nombre->num_rows > 0) {
    $row_nombre = $result_nombre->fetch_assoc();
    $nombre_alumno = $row_nombre['nombre'];
}

// Consulta para obtener las asistencias del alumno en la materia
$sql_asistencias = "SELECT id_asistencia_materia, fecha_hora, asistio FROM asistencia_materias WHERE id_alumno = $alumno_id AND materia_id = $materia_id";
$result_asistencias = $conn->query($sql_asistencias);

$asistencias = array();

if ($result_asistencias->num_rows > 0) {
    while ($row_asistencia = $result_asistencias->fetch_assoc()) {
        $asistencias[] = $row_asistencia;
    }
}

// Devolver los datos como un JSON
$response_data = array(
    'nombre' => $nombre_alumno,
    'asistencias' => $asistencias
);

echo json_encode($response_data);

// Cerrar la conexión a la base de datos
$conn->close();
?>
