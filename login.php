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

// Obtener datos del formulario
$matricula = $_POST['matricula'];
$nip = $_POST['nip'];

// Verificar el primer caracter de la matricula
$primerCaracter = substr($matricula, 0, 1);

if ($primerCaracter === 'a') {
    // Consultar la tabla de alumnos
    $sql = "SELECT * FROM alumnos WHERE matricula = '$matricula' AND nip = '$nip'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $alumno = $result->fetch_assoc();
        $idUsuario = $alumno['id_alumno'];
        // Autenticación exitosa para alumno
        header("Location: pages/tables/simple3.html?id=" . $idUsuario);
        exit();
    } else {
        // Autenticación fallida, redirigir al formulario de inicio de sesión
        header("Location: index.html");
        exit();
    }
} elseif ($primerCaracter === 'm') {
    // Consultar la tabla de maestros
    $sql = "SELECT * FROM maestros WHERE matricula = '$matricula' AND nip = '$nip'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Autenticación exitosa para maestro
        $maestro = $result->fetch_assoc();
        $idUsuario = $maestro['id_maestro'];
        // Redirigir a la página de maestro con el ID del usuario en la URL
        header("Location: pages/tables/simple2.html?id=" . $idUsuario);
        exit();
    } else {
        // Autenticación fallida, redirigir al formulario de inicio de sesión
        header("Location: index.html");
        exit();
    }
} else {
    // Carácter desconocido en la matrícula, redirigir al formulario de inicio de sesión
    header("Location: index.html");
    exit();
}

$conn->close();
?>
