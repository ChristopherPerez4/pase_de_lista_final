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

// Obtener el grupo_id de la solicitud GET
$grupoId = $_GET["grupo_id"];

// Consulta para obtener la lista de alumnos en el grupo
$sql = "SELECT id_alumno FROM alumnos_grupos WHERE grupo_id = $grupoId";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $alumnos = array();
    while ($row = $result->fetch_assoc()) {
        $idAlumno = $row["id_alumno"];
        
        // Consulta para obtener el nombre del alumno desde la tabla "alumnos"
        $sqlNombre = "SELECT nombre FROM alumnos WHERE id_alumno = $idAlumno";
        $resultNombre = $conn->query($sqlNombre);
        
        if ($resultNombre->num_rows > 0) {
            $rowNombre = $resultNombre->fetch_assoc();
            $alumno = array(
                "id_alumno" => $idAlumno,
                "nombre_alumno" => $rowNombre["nombre"]
            );
            $alumnos[] = $alumno;
        }
    }
    echo json_encode($alumnos);
} else {
    echo "No se encontraron alumnos en el grupo.";
}

$conn->close();
?>
