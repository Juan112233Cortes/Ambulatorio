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
        <h1>Medico</h1>
        <nav>
            <ul>
                <li><a href="Inicio/inicio.php">Inicio</a></li>
                <li><a href="index1.php">Paciente</a></li>
                <li><a href="index2.php">Medico</a></li>
            </ul>
        </nav>
    </header>
    <h1>Medico</h1>
    <form action="informacion_medico.php" method="POST">
        <fieldset>
            <legend><b>Médico</b></legend>
            <select name="id_medico" id="id_medico">
                <?php
                $resultadomedicoss = obtenerMedicos($conexion);

                if (is_array($resultadomedicoss)) {
                    foreach ($resultadomedicoss as $medicos) {
                        $optionText = "";
                        foreach ($medicos as $campo => $valor) {
                            $optionText .= ucfirst($campo) . ": " . $valor . " - ";
                        }
                        $optionText = rtrim($optionText, " - ");
                        echo "<option value='" . $medicos['id_medico'] . "'>" . $optionText . "</option>";
                    }
                } else {
                    echo "<option value='' disabled>No se encontraron resultados</option>";
                }
                ?>
            </select>
            <input type="submit" name="verMedico" value="Ver Médico" />
        </fieldset>
    </form>
    <footer>
        <p>&copy; 2023 Ambulatorio Quintinilla. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
