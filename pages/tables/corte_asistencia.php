<?php
// Conexión a la base de datos (reemplaza con tus propios datos)
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "rfid_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener el ID del maestro desde la solicitud AJAX
$id_maestro = $_GET["id_maestro"];

// Consultar las materias asociadas al maestro desde la tabla "maestros_materias"
$sql = "SELECT materia_id FROM maestros_materias WHERE id_maestro = $id_maestro";
$result = $conn->query($sql);

$hora_actual = date("H:i:s");
echo ($hora_actual);


$materias_maestro = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $materias_maestro[] = $row["materia_id"];
    }

    // Obtener la hora actual del sistema
    $hora_actual = date("H:i:s");
    

    // Consultar la materia más cercana en tiempo que pertenece al maestro
    $sql = "SELECT materia_id, hora_fin FROM materias WHERE materia_id IN (" . implode(",", $materias_maestro) . ") AND '$hora_actual' > hora_fin ORDER BY hora_fin ASC LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $materia_id = $row["materia_id"];
        $hora_fin_materia = $row["hora_fin"];

        echo "Hora de la materia más cercana: " . $hora_fin_materia . "<br>";

        // Obtener todos los registros de asistencia_materias con materia_id igual al encontrado
        $sql = "SELECT id_alumno FROM asistencia_materias WHERE materia_id = $materia_id";
        $result = $conn->query($sql);

        $alumnos_asistencia = array();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $alumnos_asistencia[] = $row["id_alumno"];
            }

            echo "Alumnos en asistencia_materias:";
            echo "<pre>";
            print_r($alumnos_asistencia);
            echo "</pre>";
        }

        // Obtener todos los registros de alumnos_materias con materia_id igual al encontrado
        $sql = "SELECT id_alumno FROM alumnos_materias WHERE materia_id = $materia_id";
        $result = $conn->query($sql);

        $alumnos_materias = array();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $alumnos_materias[] = $row["id_alumno"];
            }

            echo "Alumnos en alumnos_materias:";
            echo "<pre>";
            print_r($alumnos_materias);
            echo "</pre>";
        }

        // Encontrar alumnos que están en alumnos_materias pero no en asistencia_materias
        $alumnos_faltantes = array_diff($alumnos_materias, $alumnos_asistencia);

        echo "Alumnos faltantes en asistencia_materias:";
        echo "<pre>";
        print_r($alumnos_faltantes);
        echo "</pre>";

        // Insertar registros en la tabla asistencia y asistencia_materias para los alumnos faltantes
        foreach ($alumnos_faltantes as $id_alumno) {
            $hora_registro = date("H:i:s"); // Obtener la hora actual del sistema
            $fecha_registro = date("Y-m-d"); // Obtener la fecha actual del sistema

            $fecha_hora = $fecha_registro . ' ' . $hora_fin_materia;

            echo "Fecha de registro: " . $fecha_hora . "<br>";

            // Insertar en la tabla asistencia
            $sql = "INSERT INTO asistencia (id_alumno, fecha_hora, asistio) VALUES ($id_alumno, '$fecha_hora', 0)";
            $conn->query($sql);

            // Obtener el ID de la última inserción
            $id_asistencia = $conn->insert_id;

            // Insertar en la tabla asistencia_materias
            $sql = "INSERT INTO asistencia_materias (id_alumno, materia_id, fecha_hora, asistio) VALUES ($id_alumno, $materia_id, '$fecha_hora', 0)";
            $conn->query($sql);
        }

        echo "Tarea completada con éxito.";
    } else {
        echo "No hay materias futuras para registrar asistencia.";
    }
} else {
    echo "No se encontraron materias asociadas al maestro.";
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
