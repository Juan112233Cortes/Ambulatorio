<?php
    // Se incluyen archivos necesarios
    require_once("crea_tablas.php");
    require_once("metodos.php");
    require_once("consultas.php");

    // Se inicializan variables
    $id_medico = '';
    $id_paciente = '';

    // Se verifica si se ha enviado un formulario para ver información de un médico
    if (isset($_POST['verMedico'])) {
        $id_medico = mysqli_real_escape_string($conexion, $_POST["id_medico"]);
    }

    // Se obtienen las consultas del médico
    $consultas_medico = obtenerConsultaMedico($conexion, $id_medico);

    // Se obtienen detalles de la primera consulta (si existen)
    $sintomatologia = (!empty($consultas_medico) && isset($consultas_medico[0]['sintomatologia'])) ? $consultas_medico[0]['sintomatologia'] : '';
    $id_medico = (!empty($consultas_medico) && isset($consultas_medico[0]['id_medico'])) ? $consultas_medico[0]['id_medico'] : '';
    $id_paciente = (!empty($consultas_medico) && isset($consultas_medico[0]['id_paciente'])) ? $consultas_medico[0]['id_paciente'] : '';
    $fecha_consulta = (!empty($consultas_medico) && isset($consultas_medico[0]['fecha_consulta'])) ? $consultas_medico[0]['fecha_consulta'] : '';

    // Se muestra información de la primera consulta (si existen consultas)
//     if (!empty($consultas_medico)) {
//         echo "ID Médico: $id_medico<br>";
//         echo "ID Paciente: $id_paciente<br>";
//         echo "Fecha Consulta: $fecha_consulta<br>";
//     } else {
//         echo "No hay consultas para hoy.";
//     }
// ?>

<!-- Se inicia la parte HTML del documento -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Consultas Pasadas</title>
    <!-- Se enlaza la hoja de estilos CSS -->
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <!-- Encabezado de la página -->
    <header>
        <h1>Médico</h1>
        <!-- Menú de navegación -->
        <nav>
            <ul>
                <li><a href="Inicio/inicio.php">Inicio</a></li>
                <li><a href="index1.php">Paciente</a></li>
                <li><a href="index2.php">Medico</a></li>
            </ul>
        </nav>
    </header>

    <!-- Formulario para seleccionar una consulta -->
    <form action="medico_consultas.php" method="POST">
        <fieldset>
            <?php
                // Se llaman a funciones para obtener consultas próximas y del día
                obtenerConsultasProximos7Dias($conexion, $id_medico);
                obtenerConsultasHoy($conexion, $id_medico);
            ?>
            <!-- Lista desplegable con consultas disponibles -->
            <select name="id_consulta" id="id_consulta">
                <?php
                    // Se obtienen consultas del médico para llenar la lista desplegable
                    $resultadoMedicos_Formulario = obtenerConsultaMedico($conexion, $id_medico);

                    if (is_array($resultadoMedicos_Formulario)) {
                        foreach ($resultadoMedicos_Formulario as $medico) {
                            $optionText = "";
                            $camposAMostrar = ['id_consulta', 'id_paciente', 'sintomatologia'];

                            // Se construye el texto de cada opción en la lista desplegable
                            foreach ($camposAMostrar as $campo) {
                                $optionText .= ucfirst($campo) . ": " . $medico[$campo] . " - ";
                            }

                            $optionText = rtrim($optionText, " - ");

                            // Se imprime la opción en la lista desplegable
                            echo "<option value='" . $medico['id_consulta'] . "'>" . $optionText . "</option>";
                        }
                    } else {
                        echo "<option value='' disabled>No se encontraron resultados</option>";
                    }
                ?>
            </select>

            <!-- Campos ocultos con información relevante -->
            <input type='hidden' name='id_medico' value='<?php echo $id_medico; ?>' />
            <input type='hidden' name='id_paciente' value='<?php echo $id_paciente; ?>' />
            <input type='hidden' name='sintomatologia' value='<?php echo $sintomatologia; ?>' />
            <input type='hidden' name='fecha_consulta' value='<?php echo $fecha_consulta; ?>' />

            <!-- Botón para enviar el formulario -->
            <input type="submit" name="seleccionarConsulta" value="Ir a consulta" />
        </fieldset>
    </form>

    <!-- Pie de página -->
    <footer>
        <p>&copy; 2023 Ambulatorio Quintinilla. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
