<?php
// Conexión a la base de datos (reemplaza con tus datos de conexión)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rfid_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['matricula']) && isset($_POST['nip'])) {
    // Manejo de la entrada de matrícula y NIP
    $matricula = "a" . $_POST['matricula']; // Agregar automáticamente 'a' a la matrícula
    $nip = $_POST['nip'];

    // Verificar si la matrícula y el NIP existen en la tabla de alumnos
    $alumnoId = null;

    $checkSqlMatriculaNIP = "SELECT id_alumno FROM alumnos WHERE matricula = '$matricula' AND nip = '$nip'";
    $checkResultMatriculaNIP = $conn->query($checkSqlMatriculaNIP);

    if ($checkResultMatriculaNIP->num_rows > 0) {
        $alumnoId = $checkResultMatriculaNIP->fetch_assoc()['id_alumno'];
    }

    if (!is_null($alumnoId)) {
        // El alumno fue encontrado, continúa con el registro de asistencia como se hacía anteriormente.
        
        // Obten la fecha actual
        $fechaActual = date("Y-m-d");
        $horaActual = date("H:i");

        // Verifica si ya se ha registrado asistencia para el alumno hoy
        $asistenciaRegistradaSql = "SELECT COUNT(*) AS contador FROM asistencia_materias
                                     WHERE id_alumno = '$alumnoId'
                                     AND DATE(fecha_hora) = '$fechaActual'";

        $asistenciaRegistradaResult = $conn->query($asistenciaRegistradaSql);
        $asistenciaRegistradaRow = $asistenciaRegistradaResult->fetch_assoc();
        $asistenciaRegistrada = $asistenciaRegistradaRow['contador'];

        if ($asistenciaRegistrada == 0) {
            // No se ha registrado asistencia para el alumno hoy

            // Consulta para obtener la materia actual del alumno según la hora actual
            $materiaSql = "SELECT m.materia_id
                           FROM materias m
                           WHERE EXISTS (
                                SELECT 1
                                FROM alumnos_materias am
                                WHERE am.id_alumno = $alumnoId
                                AND am.materia_id = m.materia_id
                           )
                           AND '$horaActual' BETWEEN m.hora_inicio AND m.hora_fin";

            $materiaResult = $conn->query($materiaSql);

            if ($materiaResult->num_rows > 0) {
                // Si la consulta devuelve filas, significa que el alumno tiene una materia
                // en curso en este momento. Puedes obtener la 'materia_id' de la consulta.
                $materiaIdActual = $materiaResult->fetch_assoc()['materia_id'];

                // Registra la asistencia en la tabla 'asistencia_materias'
                $fechaHora = date("Y-m-d H:i:s"); // Fecha y hora actual
                $insertSqlMaterias = "INSERT INTO asistencia_materias (id_alumno, materia_id, fecha_hora, asistio) 
                                      VALUES ('$alumnoId', '$materiaIdActual', '$fechaHora', 1)";

                if ($conn->query($insertSqlMaterias) === TRUE) {
                    echo "Asistencia registrada exitosamente para la materia actual.";
                } else {
                    echo "Error al registrar la asistencia en asistencia_materias: " . $conn->error;
                }

                // Registra la asistencia en la tabla 'asistencia'
                $insertSqlGeneral = "INSERT INTO asistencia (id_alumno, fecha_hora, asistio) 
                                      VALUES ('$alumnoId', '$fechaHora', 1)";

                if ($conn->query($insertSqlGeneral) === TRUE) {
                    echo "Asistencia registrada exitosamente.";
                } else {
                    echo "Error al registrar la asistencia en asistencia: " . $conn->error;
                }
            } else {
                // Si la consulta no devuelve filas, significa que no hay clases en este momento.
                echo "No hay clases en curso en este momento para el alumno.";
            }
        } else {
            // Ya se ha registrado asistencia para el alumno hoy
            echo "Ya se ha registrado asistencia para el alumno hoy.";
        }
    } else {
        echo "Alumno no encontrado. Verifica la matrícula y el NIP proporcionados.";
    }
} elseif (isset($_POST['uid'])) {
    // Manejo de la entrada de UID desde RFID
    $uid = $_POST['uid'];

    // Verificar si el UID existe en la tabla de alumnos
    $alumnoId = null;

    $checkSqlUID = "SELECT id_alumno FROM alumnos WHERE uid = '$uid'";
    $checkResultUID = $conn->query($checkSqlUID);

    if ($checkResultUID->num_rows > 0) {
        $alumnoId = $checkResultUID->fetch_assoc()['id_alumno'];
    }

    if (!is_null($alumnoId)) {
        // El alumno fue encontrado, continúa con el registro de asistencia como se hacía anteriormente.
        
        // Obten la fecha actual
        $fechaActual = date("Y-m-d");
        $horaActual = date("H:i");

        // Verifica si ya se ha registrado asistencia para el alumno hoy
        $asistenciaRegistradaSql = "SELECT COUNT(*) AS contador FROM asistencia_materias
                                     WHERE id_alumno = '$alumnoId'
                                     AND DATE(fecha_hora) = '$fechaActual'";

        $asistenciaRegistradaResult = $conn->query($asistenciaRegistradaSql);
        $asistenciaRegistradaRow = $asistenciaRegistradaResult->fetch_assoc();
        $asistenciaRegistrada = $asistenciaRegistradaRow['contador'];

        if ($asistenciaRegistrada == 0) {
            // No se ha registrado asistencia para el alumno hoy

            // Consulta para obtener la materia actual del alumno según la hora actual
            $materiaSql = "SELECT m.materia_id
                           FROM materias m
                           WHERE EXISTS (
                                SELECT 1
                                FROM alumnos_materias am
                                WHERE am.id_alumno = $alumnoId
                                AND am.materia_id = m.materia_id
                           )
                           AND '$horaActual' BETWEEN m.hora_inicio AND m.hora_fin";

            $materiaResult = $conn->query($materiaSql);

            if ($materiaResult->num_rows > 0) {
                // Si la consulta devuelve filas, significa que el alumno tiene una materia
                // en curso en este momento. Puedes obtener la 'materia_id' de la consulta.
                $materiaIdActual = $materiaResult->fetch_assoc()['materia_id'];

                // Registra la asistencia en la tabla 'asistencia_materias'
                $fechaHora = date("Y-m-d H:i:s"); // Fecha y hora actual
                $insertSqlMaterias = "INSERT INTO asistencia_materias (id_alumno, materia_id, fecha_hora, asistio) 
                                      VALUES ('$alumnoId', '$materiaIdActual', '$fechaHora', 1)";

                if ($conn->query($insertSqlMaterias) === TRUE) {
                    echo "Asistencia registrada exitosamente para la materia actual.";
                } else {
                    echo "Error al registrar la asistencia en asistencia_materias: " . $conn->error;
                }

                // Registra la asistencia en la tabla 'asistencia'
                $insertSqlGeneral = "INSERT INTO asistencia (id_alumno, fecha_hora, asistio) 
                                      VALUES ('$alumnoId', '$fechaHora', 1)";

                if ($conn->query($insertSqlGeneral) === TRUE) {
                    echo "Asistencia registrada exitosamente.";
                } else {
                    echo "Error al registrar la asistencia en asistencia: " . $conn->error;
                }
            } else {
                // Si la consulta no devuelve filas, significa que no hay clases en este momento.
                echo "No hay clases en curso en este momento para el alumno.";
            }
        } else {
            // Ya se ha registrado asistencia para el alumno hoy
            echo "Ya se ha registrado asistencia para el alumno hoy.";
        }
    } else {
        echo "UID no encontrado en la tabla de alumnos";
    }
} else {
    echo "Datos insuficientes para el registro de asistencia.";
}

$conn->close();
?>
