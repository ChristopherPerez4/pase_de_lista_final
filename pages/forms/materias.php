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

// Recibir datos del formulario
$nombre_materia = $_POST['nombre_materia'];
$hora_inicio = $_POST['hora_inicio'];
$hora_fin = $_POST['hora_fin'];

// Verificar si el nombre de la materia ya existe en la tabla "materias"
$checkMateriaQuery = "SELECT nombre_materia FROM materias WHERE nombre_materia = '$nombre_materia'";
$resultMateria = $conn->query($checkMateriaQuery);

if ($resultMateria->num_rows > 0) {
    // El nombre de la materia ya está registrado
    $response = array("success" => false, "message" => "Error: El nombre de la materia ya está registrado");
    echo json_encode($response);
} else {
    // El nombre de la materia no existe, se puede insertar el nuevo registro
    $sql = "INSERT INTO materias (nombre_materia, hora_inicio, hora_fin) VALUES ('$nombre_materia', '$hora_inicio', '$hora_fin')";

    // Ejecutar la consulta SQL para insertar los datos
    if ($conn->query($sql) === TRUE) {
        // Después de guardar los datos con éxito
        $response = array("success" => true, "message" => "Los datos se han guardado correctamente");
        echo json_encode($response);
    } else {
        // Error al insertar
        $response = array("success" => false, "message" => "Error al crear la materia: " . $conn->error);
        echo json_encode($response);
    }
}

$conn->close();
?>
