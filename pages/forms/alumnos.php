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

    // Preparar la consulta SQL para insertar los datos en la tabla "alumnos"
    $sql = "INSERT INTO alumnos (nombre, apellidos, matricula, nip, uid)
    VALUES ('$nombre', '$apellidos', CONCAT('a', '$matricula'), '$nip', '$uid')";


    // Ejecutar la consulta SQL
    if ($conn->query($sql) === TRUE) {
        // Después de guardar los datos con éxito
        $response = array("success" => true, "message" => "Los datos se han guardado correctamente");
        echo json_encode($response);
    } else {
        echo "Error al guardar los datos: " . $conn->error;
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
}
?>