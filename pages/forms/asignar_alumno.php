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

// Obtener opciones de alumnos
$sql_alumnos = "SELECT id_alumno, nombre FROM alumnos";
$result_alumnos = $conn->query($sql_alumnos);

// Obtener opciones de grupos
$sql_grupos = "SELECT grupo_id, nombre_grupo FROM grupos";
$result_grupos = $conn->query($sql_grupos);

// Obtener opciones de materias
$sql_materias = "SELECT materia_id, nombre_materia FROM materias";
$result_materias = $conn->query($sql_materias);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Registrar Alumnos</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="btn- wrapper">
  <!-- Navbar -->
  

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="../tables/simple2.html" class="brand-link" id="verGrupos2">
      <span class="brand-text font-weight-light">Sistema de Asistencia</span>
    </a>
    

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->


      <!-- SidebarSearch Form -->


      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="../tables/simple2.html" class="nav-link" id="verGrupos">
              <i class="far fa-circle nav-icon"></i>
              <p>Ver Grupos</p>
          </a>
          </li>
          <li class="nav-item">
            <a href="../forms/registrar_alumnos.html" class="nav-link" id="registrarAlumno">
              <i class="far fa-circle nav-icon"></i>
              <p>Registrar Alumno</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="../forms/registrar_materias.html" class="nav-link" id="registrarMateria">
              <i class="far fa-circle nav-icon"></i>
              <p>Registrar Materia</p>
          </a>
          </li>
          <li class="nav-item">
            <a href="../forms/registrar_grupos.html" class="nav-link" id="registrarGrupo">
              <i class="far fa-circle nav-icon"></i>
              <p>Registrar Grupo</p>
          </a>
          </li>
          <li class="nav-item">
            <a href="../forms/asignar_alumno.php" class="nav-link" id="materiaAlumno">
              <i class="far fa-circle nav-icon"></i>
              <p>Asignar Alumno</p>
          </a>
          <li class="nav-item">
            <a href="../../index.html" class="nav-link">
              <p>Cerrar sesión</p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Asignar Alumno</h1>
          </div>

        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <!-- left column -->
      <div class="col">
        <!-- general form elements -->
        <form action="procesar_asignacion.php" method="POST">
        <label for="id_alumno">Selecciona un Alumno:</label>
        <select name="id_alumno" required>
            <?php
            if ($result_alumnos->num_rows > 0) {
                while ($row_alumno = $result_alumnos->fetch_assoc()) {
                    echo "<option value='" . $row_alumno['id_alumno'] . "'>" . $row_alumno['nombre'] . "</option>";
                }
            } else {
                echo "<option value=''>No hay alumnos disponibles</option>";
            }
            ?>
        </select><br><br>

        <label for="grupo">Grupo:</label>
        <select name="grupo" required>
            <?php
            if ($result_grupos->num_rows > 0) {
                while ($row_grupo = $result_grupos->fetch_assoc()) {
                    echo "<option value='" . $row_grupo['grupo_id'] . "'>" . $row_grupo['nombre_grupo'] . "</option>";
                }
            } else {
                echo "<option value=''>No hay grupos disponibles</option>";
            }
            ?>
        </select><br><br>

        <label for="materias[]">Materias:</label>
        <select name="materias[]" multiple required>
            <?php
            if ($result_materias->num_rows > 0) {
                while ($row_materia = $result_materias->fetch_assoc()) {
                    echo "<option value='" . $row_materia['materia_id'] . "'>" . $row_materia['nombre_materia'] . "</option>";
                }
            } else {
                echo "<option value=''>No hay materias disponibles</option>";
            }
            ?>
        </select><br><br>
        
        <div class="card-footer">
              <button type="submit" class="btn btn-primary">Asignar Alumno</button>
            </div>
    </form>
        
            <!-- /.card-body -->

          </form>
        </div>
        <!-- /.card -->        
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
      <!--/.col (right) -->
    </div>
    <!-- /.row -->
  </div><!-- /.container-fluid -->
</section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../../dist/js/demo.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
            var currentURL = window.location.href;
            var idValue = obtenerValorDeParametro("id", currentURL);
            
            // Actualizar el primer enlace
            var verGrupos = document.getElementById("verGrupos");
            if (verGrupos && idValue !== null) {
                var nuevaURL = "../tables/simple2.html?id=" + idValue;
                verGrupos.setAttribute("href", nuevaURL);
            }

            var verGrupos2 = document.getElementById("verGrupos2");
            if (verGrupos2 && idValue !== null) {
                var nuevaURL = "../tables/simple2.html?id=" + idValue;
                verGrupos2.setAttribute("href", nuevaURL);
            }
            
            // Actualizar el segundo enlace
            var registrarAlumno = document.getElementById("registrarAlumno");
            if (registrarAlumno && idValue !== null) {
                var nuevaURL = "../forms/registrar_alumnos.html?id=" + idValue;
                registrarAlumno.setAttribute("href", nuevaURL);
            }
            
            // Actualizar el tercer enlace
            var registrarMateria = document.getElementById("registrarMateria");
            if (registrarMateria && idValue !== null) {
                var nuevaURL = "../forms/registrar_materias.html?id=" + idValue;
                registrarMateria.setAttribute("href", nuevaURL);
            }

            var registrarGrupo = document.getElementById("registrarGrupo");
            if (registrarGrupo && idValue !== null) {
                var nuevaURL = "../forms/registrar_grupos.html?id=" + idValue;
                registrarGrupo.setAttribute("href", nuevaURL);
            }
            var materiaAlumno = document.getElementById("materiaAlumno");
            if (materiaAlumno && idValue !== null) {
                var nuevaURL = "../forms/asignar_alumno.php?id=" + idValue;
                materiaAlumno.setAttribute("href", nuevaURL);
            }
        
        // Función para obtener el valor de un parámetro de consulta de una URL
        function obtenerValorDeParametro(nombre, url) {
            var parametros = new URLSearchParams(new URL(url).search);
            return parametros.get(nombre);
        }
    xhr.send();
});
</script>

</body>
</html>

<?php
// Cerrar la conexión a la base de datos
$conn->close();
?>
