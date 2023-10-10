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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $id_alumno = $_POST["id_alumno"];
    $grupo = $_POST["grupo"];
    $materias = $_POST["materias"];

    // Insertar el alumno en el grupo seleccionado
    $sql_insert_alumno_grupo = "INSERT INTO alumnos_grupos (id_alumno, grupo_id) VALUES ($id_alumno, $grupo)";

    if ($conn->query($sql_insert_alumno_grupo) !== TRUE) {
        $response = array("success" => false, "message" => "Error al asignar el alumno al grupo: " . $conn->error);
        echo json_encode($response);
        exit; // Termina la ejecución para evitar procesar las materias si hay un error
    }

    // Insertar las materias seleccionadas para el alumno
    foreach ($materias as $materia_id) {
        $sql_insert_alumno_materia = "INSERT INTO alumnos_materias (id_alumno, materia_id) VALUES ($id_alumno, $materia_id)";

        if ($conn->query($sql_insert_alumno_materia) !== TRUE) {
            $response = array("success" => false, "message" => "Error al asignar la materia al alumno: " . $conn->error);
            echo json_encode($response);
            exit; // Termina la ejecución si hay un error en alguna materia
        }
    }

    // Si llega aquí, todo se ha insertado correctamente
    $response = array("success" => true, "message" => "Los datos se han guardado correctamente");
    echo json_encode($response);
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
