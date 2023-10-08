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

// Obtener el id de la materia de la solicitud GET
$materiaId = $_GET["materia_id"];

// Consulta para obtener la lista de alumnos registrados en "alumnos_materias" para esta materia
$sqlAlumnosMaterias = "SELECT alumnos.id_alumno, alumnos.nombre AS nombre_alumno
                       FROM alumnos_materias
                       INNER JOIN alumnos ON alumnos_materias.id_alumno = alumnos.id_alumno
                       WHERE alumnos_materias.materia_id = $materiaId";

$resultAlumnosMaterias = $conn->query($sqlAlumnosMaterias);

$alumnosRegistrados = array();

while ($rowAlumnoMateria = $resultAlumnosMaterias->fetch_assoc()) {
    $alumnosRegistrados[] = $rowAlumnoMateria["id_alumno"];
}

// Consulta para obtener los datos de asistencia de la materia
$sqlAsistencia = "SELECT alumnos.id_alumno, alumnos.nombre AS nombre_alumno, SUM(asistencia_materias.asistio) AS asistencias, SUM(1 - asistencia_materias.asistio) AS faltas
                  FROM asistencia_materias
                  INNER JOIN alumnos ON asistencia_materias.id_alumno = alumnos.id_alumno
                  WHERE asistencia_materias.materia_id = $materiaId
                  GROUP BY asistencia_materias.id_alumno";

$resultAsistencia = $conn->query($sqlAsistencia);

$alumnos = array();
$asistencias = array();
$faltas = array();

while ($rowAsistencia = $resultAsistencia->fetch_assoc()) {
    $alumnoId = $rowAsistencia["id_alumno"];
    $alumnos[] = $rowAsistencia["nombre_alumno"];
    $asistencias[] = (int) $rowAsistencia["asistencias"];
    $faltas[] = (int) $rowAsistencia["faltas"];

    // Eliminar al alumno registrado de la lista para asegurarse de que no se duplique
    $key = array_search($alumnoId, $alumnosRegistrados);
    if ($key !== false) {
        unset($alumnosRegistrados[$key]);
    }
}

// Asignar faltas a los alumnos registrados pero que no están en la tabla de asistencia
foreach ($alumnosRegistrados as $alumnoIdRegistrado) {
    $sqlFalta = "SELECT nombre FROM alumnos WHERE id_alumno = $alumnoIdRegistrado";
    $resultFalta = $conn->query($sqlFalta);
    if ($resultFalta->num_rows > 0) {
        $rowFalta = $resultFalta->fetch_assoc();
        $alumnos[] = $rowFalta["nombre"];
        $asistencias[] = 0; // Asistencia igual a 0 para indicar falta
        $faltas[] = 1;     // Falta igual a 1
    }
}

$data = array(
    "alumnos" => $alumnos,
    "asistencias" => $asistencias,
    "faltas" => $faltas
);

// Devolver los datos como JSON
header("Content-Type: application/json");
echo json_encode($data);

$conn->close();
?>
