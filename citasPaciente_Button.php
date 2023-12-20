<?php
    // Incluye archivos necesarios
    require_once("crea_tablas.php");
    require_once("metodos.php");
    include("consultas.php");

    // Inicializa variables
    $id_paciente = '';
    $id_medico = '';

    // Verifica si se ha enviado la solicitud para ver información del paciente
    if (isset($_POST['verPaciente'])) {
        // Escapa el valor del campo "id_paciente" para prevenir inyección SQL
        $id_paciente = mysqli_real_escape_string($conexion, $_POST["id_paciente"]);
    }

    // Muestra el ID del paciente y del médico (actualmente $id_medico está vacío)
        // echo $id_paciente;
        // echo "<br>";
        // echo $id_medico;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Consultas Pasadas</title>
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

<form action="index1_2.php" method="POST">
    <?php
    // Inserta información sobre el paciente y su medicación
    insertar_info_paciente($conexion, $id_paciente);
    insertar_info_medicacion($conexion, $id_paciente);
    ?>

    <fieldset>
        <legend>Consultar Citas Pasadas</legend>

        <?php
            // Verifica si se ha seleccionado un paciente
            if (isset($id_paciente)) {
                // Incluye campos ocultos con el ID del paciente y del médico actual
                echo "<input type='hidden' name='id_paciente' value='" . $id_paciente . "' />";
                echo "<input type='hidden' name='id_paciente' value='" . $id_medico . "' />";
            } else {
                echo "<p>No se encontró información para el paciente seleccionado</p>";
            }
        ?>

        <input type="submit" name="verConsultaPasadas" value="Ver Consultas"/>
    </fieldset>
</form>

<footer>
    <p>&copy; 2023 Ambulatorio Quintinilla. Todos los derechos reservados.</p>
</footer>

</body>
</html>
