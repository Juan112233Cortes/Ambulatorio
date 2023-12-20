<?php
require_once("conecta.php");
require_once("metodos.php");
require_once("crea_tablas.php");

$id_paciente = '';
$id_medico = '';


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pedirCita'])) {
  // Verificar que id_medico e id_paciente estén presentes y sean números válidos
  if (isset($_POST['id_medico']) && isset($_POST['id_paciente']) ) {
      $id_medico = mysqli_real_escape_string($conexion, $_POST['id_medico']);
      $id_paciente = mysqli_real_escape_string($conexion, $_POST['id_paciente']);

      // Otros campos del formulario
      $fecha_consulta_raw = isset($_POST['fecha_consulta']) ? $_POST['fecha_consulta'] : '';
      $sintomatologia = isset($_POST['sintomatologia']) ? mysqli_real_escape_string($conexion, $_POST['sintomatologia']) : '';

      // Verificar si la fecha está presente y no es una cadena vacía
      if (!empty($fecha_consulta_raw)) {
          // Formatear la fecha
          $fecha_consulta = date('Y-m-d', strtotime($fecha_consulta_raw));

          // Realizar el INSERT en la base de datos
          $query = "INSERT INTO consulta (id_medico, id_paciente, fecha_consulta, sintomatologia) 
                    VALUES ('$id_medico', '$id_paciente', '$fecha_consulta', '$sintomatologia')";

          // Ejecutar la consulta
          if (mysqli_query($conexion, $query)) {
              echo "Consulta enviada con éxito.";
                   } else {
                       echo "Error al enviar la consulta: " . mysqli_error($conexion);
                   }
               } else {
                   echo "La fecha de consulta es inválida.";
                }
           } else {
               echo "ID de médico o ID de paciente no válido.";
          }
}
// echo $id_paciente;
// echo $id_medico;
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <link rel="stylesheet" href="style/style.css">
    <style>
        body {
         font-family: Arial, sans-serif;
         background-color: #f4f4f4;
         margin: 0;
         padding: 0;
        }

        form {
          max-width: 600px;
         margin: 20px auto;
          padding: 20px;
          background-color: #fff;
          border-radius: 8px;
          box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        fieldset {
         border: 1px solid #ddd;
          padding: 10px;
         margin-bottom: 15px;
        }

        label {
         display: block;
         margin-bottom: 8px;
        }

        input[type="text"],
        textarea,
        input[type="date"] {
         width: 100%;
          padding: 10px;
          margin-bottom: 15px;
          border: 1px solid #ddd;
          border-radius: 4px;
          box-sizing: border-box;
        }

        textarea {
          resize: vertical;
        }

        button {
          background-color: #4caf50;
          color: #fff;
          padding: 10px 15px;
          border: none;
          border-radius: 4px;
          cursor: pointer;
          font-size: 16px;
        }
        
        button:hover {
          background-color: #45a049;
        }

    </style>
</head>
<body>
<header>
        <h1>Paciente</h1>
        <nav>
            <ul>
                <li><a href="Inicio/inicio.php">Inicio</a></li>
                <li><a href="index1.php">Paciente</a></li>
                <li><a href="index2.php">Medico</a></li>
            </ul>
        </nav>
    </header>
<form action="" method="POST" onsubmit="return validarFecha()">
        <fieldset>
            <legend><b>Pedir cita</b></legend>
            <!-- Utiliza los valores arrastrados para id_medico e id_paciente -->
            <input type="hidden" name="id_medico" value="<?php echo $id_medico; ?>">
            <input type="hidden" name="id_paciente" value="<?php echo $id_paciente; ?>">
            <?php
                // Verificar si se ha seleccionado un paciente
                if (isset($id_paciente)) {
                    // Incluir un campo oculto con el ID del paciente actual
                    echo "<input type='hidden' name='id_paciente' value='" . $id_paciente . "' />";
                    echo "<input type='hidden' name='id_medico' value='" . $id_medico . "' />";

                } else {
                    echo "<p>No se encontró información para el paciente seleccionado</p>";
                }
            ?>
            <label for="fecha_consulta">Fecha de Consulta:</label>
            <input type="date" id="fecha_consulta" name="fecha_consulta" required oninput="validarFecha()">

            <label for="sintomatologia">Sintomatología:</label>
            <textarea id="sintomatologia" name="sintomatologia" rows="4" required></textarea>

            <button type="submit" name="pedirCita">Enviar Consulta</button>
        </fieldset>
    </form>
    <script>
    function validarFecha() {
      var inputFecha = document.getElementById('fecha_consulta');
      var fechaSeleccionada = new Date(inputFecha.value);
      var hoy = new Date(); // Obtiene la fecha y hora actuales y las almacena en la variable hoy.
      var unMesDespues = new Date(hoy.getTime() + 30 * 24 * 60 * 60 * 1000);//30 dias, 24h, 60min/h, 60seg/min, 1000 milis/seg

      // Verificar si la fecha seleccionada es igual al día actual
      if (fechaSeleccionada.toDateString() === hoy.toDateString()) {
        // Si es el día actual, simplemente retorna true sin mostrar alerta
        return true;
      } else if (fechaSeleccionada < hoy) {
        alert('Fecha no válida');
        return false;
      } else if (fechaSeleccionada.getDay() === 0 || fechaSeleccionada.getDay() === 6) {//devuelve un numero entre 0 y 6 --- 0=domingo y 6=sabado
        alert('Por favor, elija un día laborable');
        return false;
      } else if (fechaSeleccionada > unMesDespues) {
        alert('Tan malo no estarás. Pide una fecha como máximo 30 días en el futuro');
        return false;
      } else {
        return true;
      }
    }
</script>
    <footer>
        <p>&copy; 2023 Ambulatorio Quintinilla. Todos los derechos reservados.</p>
    </footer>
</body>
</html>