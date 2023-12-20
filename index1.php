<?php
    require_once("crea_tablas.php");
    include("consultas.php");
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
    <h1>Paciente</h1>
    <form action="citasPaciente_Button.php" method="POST">
        <fieldset>
            <legend><b>Paciente</b></legend>
            <select name="id_paciente" id="id_paciente">
            <?php
                // Obtiene la lista de pacientes desde la base de datos
                $resultadoPacientes = obtenerPacientes($conexion);

                // Verifica si el resultado es un array
                if (is_array($resultadoPacientes)) {
                    // Recorre cada paciente en el array
                    foreach ($resultadoPacientes as $paciente) {
                        // Inicializa una cadena para almacenar el texto de la opción
                        $optionText = "";

                        // Recorre cada campo y valor del paciente
                        foreach ($paciente as $campo => $valor) {
                            // Construye el texto de la opción en formato "Campo: Valor - "
                            $optionText .= ucfirst($campo) . ": " . $valor . " - ";
                        }

                        // Elimina el último " - " de la cadena
                        $optionText = rtrim($optionText, " - ");

                        // Imprime la etiqueta <option> con el valor y texto correspondientes
                        echo "<option value='" . $paciente['id_paciente'] . "'>" . $optionText . "</option>";
                    }
                } else {
                    // Si no hay resultados, muestra una opción deshabilitada
                    echo "<option value='' disabled>No se encontraron resultados</option>";
                }
                ?>

            </select>
            <input type="submit" name="verPaciente" value="Ver Paciente" />
        </fieldset>
    </form>
    <footer>
        <p>&copy; 2023 Ambulatorio Quintinilla. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
