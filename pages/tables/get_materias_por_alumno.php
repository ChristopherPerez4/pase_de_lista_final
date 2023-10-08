<?php
// Conectar a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$database = "rfid_db";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener el id_alumno de la solicitud GET
$idAlumno = $_GET["id_alumno"];

// Consulta para obtener las materias en las que está inscrito el alumno
$sql = "SELECT m.nombre_materia, m.materia_id FROM alumnos_materias am JOIN materias m ON am.materia_id = m.materia_id WHERE am.id_alumno = $idAlumno";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $materias = array();
    while ($row = $result->fetch_assoc()) {
        $materias[] = $row;
    }
    echo json_encode($materias);
} else {
    echo "El alumno no está inscrito en ninguna materia.";
}

$conn->close();
?>
