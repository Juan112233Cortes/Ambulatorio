<?php
require_once("conecta.php");
require_once("medico_consultas.php");

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['enviarConsulta'])) {
    // Obtener datos del formulario
    $id_medico = mysqli_real_escape_string($conexion, $_POST['id_medico']);
    $id_paciente = mysqli_real_escape_string($conexion, $_POST['id_paciente']);
    $fecha_consulta = mysqli_real_escape_string($conexion, $_POST['fecha_consulta']);
    $diagnostico = mysqli_real_escape_string($conexion, $_POST['diagnostico']);
    $sintomatologia = isset($_POST['sintomatologia']) ? mysqli_real_escape_string($conexion, $_POST['sintomatologia']) : "";
    $pdf_path = "";

    // Procesar el archivo PDF si se ha subido
    if (isset($_FILES['pdf']) && $_FILES['pdf']['size'] > 0) {
        $pdf_path = "pdf/" . basename($_FILES['pdf']['name']);
        move_uploaded_file($_FILES['pdf']['tmp_name'], $pdf_path);
    }

    // Actualizar consulta en la base de datos
    $sqlUpdateConsulta = "UPDATE consulta 
                     SET diagnostico = '$diagnostico', sintomatologia = '$sintomatologia', pdf = '$pdf_path'
                     WHERE id_medico = '$id_medico' AND id_paciente = '$id_paciente' AND fecha_consulta = '$fecha_consulta'";

    if (mysqli_query($conexion, $sqlUpdateConsulta)) {
        echo "Consulta actualizada correctamente en la base de datos.";
    } else {
        echo "Error al actualizar consulta: " . mysqli_error($conexion);
    }

    // Obtener el ID de la consulta recién insertada
    $id_consulta = mysqli_insert_id($conexion);

    // Insertar información de medicamentos en la tabla receta
    if (isset($_POST['medicationIds'])) {
        $medicationIds = $_POST['medicationIds'];
        $quantities = $_POST['quantities'];
        $frequencies = $_POST['frequencies'];
        $days = $_POST['days'];
        $chronics = $_POST['chronics'];

        foreach ($medicationIds as $index => $medicationId) {
            $quantity = $quantities[$index];
            $frequency = $frequencies[$index];
            $day = $days[$index];
            $chronic = $chronics[$index];

            // Insertar en la tabla receta
            $sqlInsertReceta = "INSERT INTO receta (id_medicamento, id_consulta, posologia, fecha_fin)
                                VALUES ('$medicationId', '$id_consulta', '$quantity', '$frequency', " .
                ($chronic == "1" ? "NULL" : "CURDATE() + INTERVAL $day DAY") . ")";

            if (mysqli_query($conexion, $sqlInsertReceta)) {
                echo "Receta insertada correctamente en la base de datos.";
            } else {
                echo "Error al insertar receta: " . mysqli_error($conexion);
            }
        }
    }
}

    // Función para extraer el valor de un campo específico
    function extractValue($details, $field){
        foreach ($details as $detail) {
            if (strpos($detail, $field) !== false) {
                return trim(str_replace("$field: ", "", $detail));
            }
        }
        return "";
    }


?>
