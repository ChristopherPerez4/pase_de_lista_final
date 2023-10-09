<?php
// Verificar si se proporcionó el parámetro 'id_alumno' en la URL
if (isset($_GET['id'])) {
    // Obtener el ID del alumno de la URL
    $idAlumno = $_GET['id'];
    
    // Conectar a la base de datos (reemplaza con tus credenciales)
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "rfid_db";
    

    $conn = new mysqli($servername, $username, $password, $database);
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Consulta SQL para obtener las materias del alumno
    $sql = "SELECT m.nombre_materia, m.materia_id
            FROM alumnos_materias am
            JOIN materias m ON am.materia_id = m.materia_id
            WHERE am.id_alumno = $idAlumno";

    $result = $conn->query($sql);

    // Crear un arreglo para almacenar las materias
    $materias = array();

    // Verificar si se encontraron materias
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $materias[] = $row;
        }
    }

    // Cerrar la conexión a la base de datos
    $conn->close();

    // Devolver las materias como una respuesta JSON
    header('Content-Type: application/json');
    echo json_encode($materias);
} else {
    echo "ID de alumno no proporcionado en la URL.";
}
?>
