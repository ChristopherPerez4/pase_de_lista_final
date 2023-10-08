<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$database = "rfid_db";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el ID del maestro (supongamos que lo pasas como parámetro)
$idMaestro = $_GET['id_maestro']; // Cambia esto según cómo obtienes el ID del maestro

// Consulta SQL para obtener los grupos que el maestro dirige
$sql = "SELECT grupos.grupo_id, grupos.nombre_grupo
        FROM grupos
        INNER JOIN maestros_grupos ON grupos.grupo_id = maestros_grupos.grupo_id
        WHERE maestros_grupos.id_maestro = $idMaestro";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $grupos = array();
    while ($row = $result->fetch_assoc()) {
        $grupos[] = $row;
    }
    echo json_encode($grupos);
} else {
    echo "El maestro no dirige ningún grupo.";
}

$conn->close();
?>
