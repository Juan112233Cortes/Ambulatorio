<?php
// ver_pdf.php

require_once("conecta.php");
require_once("metodos.php");

// Inicializa la variable $id_paciente
$id_paciente = '';

// Verifica si se ha enviado la solicitud para ver el PDF
if (isset($_POST['verPDF'])) {
    // Escapa el valor del campo "id_paciente" para prevenir inyección SQL
    $id_paciente = mysqli_real_escape_string($conexion, $_POST["id_paciente"]);
}

// Llama a la función insertar_pfd para mostrar el PDF asociado al paciente
insertar_pfd($conexion, $id_paciente);


?>