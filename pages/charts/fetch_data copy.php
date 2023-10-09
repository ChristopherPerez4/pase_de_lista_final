<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rfid_db";

// Get the group ID from the query parameters
$grupo_id = $_GET['grupo_id'];

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data from the 'alumnos_grupos' table for the selected group
$sql = "SELECT id_alumno FROM alumnos_grupos WHERE grupo_id = $grupo_id";
$result = $conn->query($sql);

$asistencias = 0;
$faltas = 0;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Check if the student's id_alumno is in the 'asistencia' table
        $id_alumno = $row["id_alumno"];
        $sql_check = "SELECT asistio FROM asistencia WHERE id_alumno = $id_alumno";
        $result_check = $conn->query($sql_check);

        if ($result_check->num_rows > 0) {
            // If there is a record for the student in 'asistencia'
            while ($row_check = $result_check->fetch_assoc()) {
                if ($row_check["asistio"] == 1) {
                    // Student attended
                    $asistencias++;
                } else {
                    // Student didn't attend
                    $faltas++;
                }
            }
        } else {
            // If there is no record for the student in 'asistencia', consider it as a absence
            $faltas++;
        }
    }
}

// Return data as JSON
$response = array(
    "asistencias" => $asistencias,
    "faltas" => $faltas,
);

echo json_encode($response);

$conn->close();
?>
