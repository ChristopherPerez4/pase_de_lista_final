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

    if ($conn->query($sql_insert_alumno_grupo) === TRUE) {
        echo "Alumno asignado al grupo exitosamente.<br>";
    } else {
        echo "Error al asignar el alumno al grupo: " . $conn->error . "<br>";
    }

    // Insertar las materias seleccionadas para el alumno
    foreach ($materias as $materia_id) {
        $sql_insert_alumno_materia = "INSERT INTO alumnos_materias (id_alumno, materia_id) VALUES ($id_alumno, $materia_id)";

        if ($conn->query($sql_insert_alumno_materia) === TRUE) {
            echo "Materia asignada al alumno exitosamente.<br>";
        } else {
            echo "Error al asignar la materia al alumno: " . $conn->error . "<br>";
        }
    }
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
