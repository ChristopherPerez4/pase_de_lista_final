<?php
// Datos de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rfid_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Obtener los datos del formulario
$id_alumno = $_POST["id_alumno"];
$materia_id = $_POST["materia_id"];

// Consulta SQL para insertar la relación en la tabla "alumnos_materias"
$sql_insert = "INSERT INTO alumnos_materias (id_alumno, materia_id) VALUES ('$id_alumno', '$materia_id')";

if ($conn->query($sql_insert) === TRUE) {
    echo "La relación alumno-materia se ha guardado correctamente.";
} else {
    echo "Error al guardar la relación: " . $conn->error;
}

$conn->close();
?>
