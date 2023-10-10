<?php
// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conexión a la base de datos (ajusta estos valores según tu configuración)
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "rfid_db";

    $conn = new mysqli($servername, $username, $password, $database);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Error en la conexión a la base de datos: " . $conn->connect_error);
    }

    // Obtener los datos del formulario
    $nombre = $_POST["nombre"];
    $apellidos = $_POST["apellidos"];
    $matricula = $_POST["matricula"];
    $nip = $_POST["nip"];
    $uid = $_POST["uid"];

    // Verificar si la matrícula ya existe en la tabla "alumnos"
    $checkMatriculaQuery = "SELECT matricula FROM alumnos WHERE matricula = '$matricula'";
    $resultMatricula = $conn->query($checkMatriculaQuery);

    // Verificar si el NIP ya existe en la tabla "alumnos"
    $checkNipQuery = "SELECT nip FROM alumnos WHERE nip = '$nip'";
    $resultNip = $conn->query($checkNipQuery);

    if ($resultMatricula->num_rows > 0) {
        // La matrícula ya está registrada
        $response = array("success" => false, "message" => "Error: La matrícula ya está registrada");
        echo json_encode($response);
    } elseif ($resultNip->num_rows > 0) {
        // El NIP ya está registrado
        $response = array("success" => false, "message" => "Error: El NIP ya está registrado");
        echo json_encode($response);
    } else {
        // Ni la matrícula ni el NIP existen, se puede insertar el nuevo registro
        $sql = "INSERT INTO alumnos (nombre, apellidos, matricula, nip, uid)
        VALUES ('$nombre', '$apellidos', CONCAT('a', '$matricula'), '$nip', '$uid')";

        // Ejecutar la consulta SQL para insertar los datos
        if ($conn->query($sql) === TRUE) {
            // Después de guardar los datos con éxito
            $response = array("success" => true, "message" => "Los datos se han guardado correctamente");
            echo json_encode($response);
        } else {
            // Error al insertar
            $response = array("success" => false, "message" => "Error al guardar los datos");
            echo json_encode($response);
        }
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
}
?>
