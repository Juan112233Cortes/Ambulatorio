<?php
require_once("conecta.php");
require_once("metodos.php");
require_once("crea_tablas.php");

$id_paciente = '';
$id_medico = '';

// Verifica si se ha enviado la solicitud para ver información de consultas pasadas
if (isset($_POST['verInfoConsultaPasadas'])) {
    // Escapa el valor del campo "id_paciente" para prevenir inyección SQL
    $id_paciente = mysqli_real_escape_string($conexion, $_POST["id_paciente"]);
}

// Se pueden imprimir los valores para verificar durante el desarrollo
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

<form action="ver_pdf.php" method="POST">
    <?php
    // Inserta información sobre el paciente, su medicación y consultas pasadas
    insertar_info_paciente($conexion, $id_paciente);
    insertar_info_medicacion($conexion, $id_paciente);
    insertar_consultas_pasadas($conexion, $id_paciente);
    ?>
    <fieldset>
        <legend><b>Paciente</b></legend>
        <!-- Selección de paciente para ver PDF -->
        <select name="id_paciente" id="id_paciente">
            <?php
            $resultadoPacientes = obtenerConsultasPasadas($conexion, $id_paciente);

            if (is_array($resultadoPacientes)) {
                foreach ($resultadoPacientes as $paciente) {
                    $optionText = "";
                    foreach ($paciente as $campo => $valor) {
                        $optionText .= ucfirst($campo) . ": " . $valor . " - ";
                    }
                    $optionText = rtrim($optionText, " - ");
                    echo "<option value='" . $paciente['id_paciente'] . "'>" . $optionText . "</option>";
                }
            } else {
                echo "<option value='' disabled>No se encontraron resultados</option>";
            }
            ?>
        </select>
        <input type="submit" name="verPDF" value="Ver PDF" />
    </fieldset>
</form>

<form action="index1_2_3_pedirCita.php" method="POST">
    <?php
    // Verifica si se ha seleccionado un paciente
    if (isset($id_paciente)) {
        // Incluye campos ocultos con el ID del paciente y del médico actual
        echo "<input type='hidden' name='id_paciente' value='" . $id_paciente . "' />";
        echo "<input type='hidden' name='id_medico' value='" . $id_medico . "' />";
    } else {
        echo "<p>No se encontró información para el paciente seleccionado</p>";
    }
    ?>
    <!-- Botón para pedir cita -->
    <input type="submit" name="pedirCita" value="Pedir Cita" />
    <?php
    // Se pueden imprimir los valores para verificar durante el desarrollo
    echo $id_paciente;
    echo $id_medico;
    ?>
</form>

<footer>
    <p>&copy; 2023 Ambulatorio Quintinilla. Todos los derechos reservados.</p>
</footer>

</body>
</html>
