<?php
// Conexión a la base de datos (reemplaza estos valores con los tuyos)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rfid_db";

// Obtener id_alumno y materia_id de la solicitud AJAX
$alumno_id = $_GET["alumno_id"];
$materia_id = $_GET["materia_id"];

// Inicializar el resultado
$resultado = array();

// Realizar consultas en las tablas para determinar asistencias y faltas
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta para obtener el número máximo de asistencias
    $sql = "SELECT COUNT(*) as max_asistencias FROM asistencia_materias WHERE id_alumno = :alumno_id AND materia_id = :materia_id AND asistio = 1";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':alumno_id', $alumno_id, PDO::PARAM_INT);
    $stmt->bindParam(':materia_id', $materia_id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch();
    $max_asistencias = $row['max_asistencias'];

    // Consulta para verificar si el alumno está registrado en la tabla "alumnos_materias" para esta materia
    $sql = "SELECT COUNT(*) as registros FROM alumnos_materias WHERE id_alumno = :alumno_id AND materia_id = :materia_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':alumno_id', $alumno_id, PDO::PARAM_INT);
    $stmt->bindParam(':materia_id', $materia_id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch();
    $registros_alumnos_materias = $row['registros'];

    // Consulta para obtener el nombre del alumno
    $sql = "SELECT nombre FROM alumnos WHERE id_alumno = :alumno_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':alumno_id', $alumno_id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch();
    $nombre_alumno = $row['nombre'];

    // Calcular faltas
    $asistencias = 0; // Inicializar $asistencias
    if ($registros_alumnos_materias > 0) {
        // Consulta para obtener el número actual de asistencias
        $sql = "SELECT COUNT(*) as asistencias FROM asistencia_materias WHERE id_alumno = :alumno_id AND materia_id = :materia_id AND asistio = 1";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':alumno_id', $alumno_id, PDO::PARAM_INT);
        $stmt->bindParam(':materia_id', $materia_id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();
        $asistencias = $row['asistencias'];
    }

    // Consulta para obtener el número de faltas de alumnos registrados en "alumnos_materias" pero no en "asistencia_materias"
    if ($registros_alumnos_materias > 0) {
        $sql = "SELECT COUNT(*) as faltas FROM alumnos_materias WHERE id_alumno = :alumno_id AND materia_id = :materia_id AND NOT EXISTS (SELECT 1 FROM asistencia_materias WHERE id_alumno = :alumno_id AND materia_id = :materia_id)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':alumno_id', $alumno_id, PDO::PARAM_INT);
        $stmt->bindParam(':materia_id', $materia_id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();
        $faltas = $row['faltas'];
    } else {
        $faltas = $max_asistencias;
    }

    // Nueva condición: Si el alumno tiene "0" en el campo "asistio" en la tabla "asistencia_materias", se considera falta.
    $sql = "SELECT COUNT(*) as asistio_cero FROM asistencia_materias WHERE id_alumno = :alumno_id AND materia_id = :materia_id AND asistio = 0";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':alumno_id', $alumno_id, PDO::PARAM_INT);
    $stmt->bindParam(':materia_id', $materia_id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch();
    $faltas += $row['asistio_cero'];

    // Preparar los datos para enviar como respuesta
    $resultado['nombre_alumno'] = $nombre_alumno;
    $resultado['asistencias'] = $asistencias;
    $resultado['faltas'] = $faltas;
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Devolver los datos como JSON
echo json_encode($resultado);

// Cerrar la conexión a la base de datos
$conn = null;
?>
