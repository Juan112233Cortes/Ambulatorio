<?php
    // Se incluyen archivos necesarios
    require_once("crea_tablas.php");
    require_once("metodos.php");
    require_once("consultas.php");
    require_once("conecta.php");

    // Se inicializan variables
    $id_medico = '';
    $id_paciente = '';
    $id_consulta = '';
    $fecha_consulta = '';
    $sintomatologia = '';
    $diagnostico = '';

    // Se verifica si el formulario ha sido enviado por el método POST
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['seleccionarConsulta'])) {

        // Verificar que id_consulta, id_medico, e id_paciente estén presentes y sean números válidos
        if (isset($_POST['id_consulta']) && isset($_POST['id_medico']) && isset($_POST['id_paciente'])) {
            $id_consulta = mysqli_real_escape_string($conexion, $_POST['id_consulta']);
            $id_medico = mysqli_real_escape_string($conexion, $_POST['id_medico']);
            $id_paciente = mysqli_real_escape_string($conexion, $_POST['id_paciente']);
            $fecha_consulta = mysqli_real_escape_string($conexion, $_POST['fecha_consulta']);
    
            $sintomatologia = isset($_POST['sintomatologia']) ? mysqli_real_escape_string($conexion, $_POST['sintomatologia']) : "";
            $diagnostico = isset($_POST['diagnostico']) ? mysqli_real_escape_string($conexion, $_POST['diagnostico']) : "";
        }
    }
?>

<!-- Se inicia la parte HTML del documento -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <!-- Se enlaza la hoja de estilos CSS -->
    <link rel="stylesheet" href="style/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        form {
            max-width: 600px;
            margin: 20px auto;
        }
        label {
            display: block;
            margin-bottom: 8px;
        }
        input, textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            box-sizing: border-box;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        #medicationList {
            list-style-type: none;
            padding: 0;
        }

        #medicationList li {
            background-color: #f2f2f2;
            padding: 8px;
            margin-bottom: 8px;
            border-radius: 4px;
        }
    </style>
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
    <br>
    <!-- Formulario para cargar información de la consulta -->
    <form action="cargar_consulta.php" method="POST" enctype="multipart/form-data" id="consultaForm">
        <fieldset>
            <!-- Campos ocultos con información relevante -->
            <input type='hidden' name='id_consulta' value='<?php echo $id_consulta; ?>' />
            <input type='hidden' name='id_medico' value='<?php echo $id_medico; ?>' />
            <input type='hidden' name='id_paciente' value='<?php echo $id_paciente; ?>' />
            <input type='hidden' name='fecha_consulta' value='<?php echo $fecha_consulta; ?>' />

            <?php
                // Verificar si se ha seleccionado la consulta
                if (isset($id_consulta)) {
                    // Incluir campos ocultos con información adicional
                    echo "<input type='hidden' name='id_medico' value='" . $id_medico . "' />";
                    echo "<input type='hidden' name='id_medico' value='" . $id_paciente . "' />";
                    echo "<input type='hidden' name='id_medico' value='" . $fecha_consulta . "' />";
                } else {
                    echo "<p>No se encontró información para la consulta</p>";
                }
            ?>

            <!-- Campos del formulario para ingresar información -->
            <label for="id_medico">ID Médico:</label>
            <input type="text" id="id_medico" name="id_medico" value="<?php echo $id_medico; ?>" readonly required>

            <label for="id_paciente">ID Paciente:</label>
            <input type="text" id="id_paciente" name="id_paciente" value="<?php echo $id_paciente; ?>" readonly required>

            <label for="fecha_consulta">Fecha de Consulta:</label>
            <input type="text" id="fecha_consulta" name="fecha_consulta" value="<?php echo $fecha_consulta; ?>" readonly required>

            <label for="sintomatologia">Sintomatología:</label>
            <input type="text" id="sintomatologia" name="sintomatologia" value="<?php echo $sintomatologia; ?>" required>

            <label for="diagnostico">Diagnóstico:</label>
            <input type="text" id="diagnostico" name="diagnostico">

            <!-- Sección para medicamentos -->
            <fieldset>
                <legend>Medicamento:</legend>
                <!-- Lista desplegable para seleccionar medicamento -->
                <select id="medication">
                <?php
                    $resultadomedicoss = obtenerMedicamentos($conexion);

                    if (is_array($resultadomedicoss)) {
                        foreach ($resultadomedicoss as $medicamento) {
                            $optionText = "";
                            foreach ($medicamento as $campo => $valor) {
                                $optionText .= ucfirst($campo) . ": " . $valor . " - ";
                            }
                            $optionText = rtrim($optionText, " - ");
                            echo "<option value='" . $medicamento['id_medicamento'] . "'>" . $optionText . "</option>";
                        }
                    } else {
                        echo "<option value='' disabled>No se encontraron resultados</option>";
                    }
                ?>
                </select>

                <!-- Campos para ingresar detalles del medicamento -->
                <label for="quantity">Cantidad:</label>
                <input type="text" id="quantity" placeholder="Ej. Media pastilla">

                <label for="frequency">Frecuencia:</label>
                <input type="text" id="frequency" placeholder="Ej. Cada 8h">

                <label for="days">Número de días:</label>
                <input type="text" id="days" placeholder="Ej. 3 días">

                <label>
                  <input type="checkbox" id="chronic"> Medicación crónica
                </label>

                <!-- Botón para añadir medicación -->
                <button type="button" onclick="añadirMedicamento()">Añadir medicación</button>

                <!-- Lista de medicación añadida -->
                <div id="medicationList">
                    <!-- La lista de medicación se mostrará aquí -->
                </div>
                
            <!-- Script JavaScript para la funcionalidad de añadir medicación -->
            <script>
                // document.addEventListener('DOMContentLoaded', function () {
                //     var form = document.getElementById('consultaForm');

                //     form.addEventListener('submit', function (event) {
                //         // Agregar información de medicamentos al formulario antes de enviarlo
                //         var medicationList = document.getElementById('medicationList');
                //         var medications = medicationList.getElementsByTagName('li');
                //         var medicationIds = [];
                //         var quantities = [];
                //         var frequencies = [];
                //         var days = [];
                //         var chronics = [];

                //         for (var i = 0; i < medications.length; i++) {
                //             var medicationInfo = medications[i].textContent;
                //             var details = medicationInfo.split(' - ');

                //             medicationIds.push(details[0]); // Assuming medication ID is the first part
                //             quantities.push(extractValue(details, 'Cantidad'));
                //             frequencies.push(extractValue(details, 'Frecuencia'));
                //             days.push(extractValue(details, 'Días'));
                //             chronics.push(details[details.length - 1].trim() === 'Crónica' ? '1' : '0');
                //         }

                //         // Crear campos ocultos con la información de los medicamentos
                //         addHiddenField(form, 'medicationIds', JSON.stringify(medicationIds));
                //         addHiddenField(form, 'quantities', JSON.stringify(quantities));
                //         addHiddenField(form, 'frequencies', JSON.stringify(frequencies));
                //         addHiddenField(form, 'days', JSON.stringify(days));
                //         addHiddenField(form, 'chronics', JSON.stringify(chronics));
                //     });
                // });

                // function extractValue(details, field) {
                //     for (var i = 0; i < details.length; i++) {
                //         if (details[i].indexOf(field) !== -1) {
                //             return details[i].replace(field + ': ', '').trim();
                //         }
                //     }
                //     return '';
                // }

                // function addHiddenField(form, name, value) {
                //     var hiddenField = document.createElement('input');
                //     hiddenField.type = 'hidden';
                //     hiddenField.name = name;
                //     hiddenField.value = value;
                //     form.appendChild(hiddenField);
                // }

                function añadirMedicamento() {
                    var medicationSelect = document.getElementById("medication");
                    var quantity = document.getElementById("quantity").value;
                    var frequency = document.getElementById("frequency").value;
                    var days = document.getElementById("days").value;
                    var chronic = document.getElementById("chronic").checked;

                    var selectedMedicationOption = medicationSelect.options[medicationSelect.selectedIndex];
                    var medicationText = selectedMedicationOption.textContent;

                    if (!medicationText) {
                        alert("Por favor, selecciona una medicación.");
                    } else if (quantity.length === 0) {
                        alert("Por favor, ingresa la cantidad.");
                    } else if (frequency.length === 0) {
                        alert("Por favor, ingresa la frecuencia.");
                    } else if (!chronic && days.length === 0) {
                        alert("Por favor, ingresa los días o marca la opción crónica.");
                    } else if (chronic && days.length > 0) {
                        alert("Si seleccionas la opción crónica, no debes ingresar días.");
                    } else if (quantity.length >= 100) {
                        alert("La cantidad no puede tener más de 100 caracteres.");
                    } else if (days.length >= 100) {
                        alert("Los días no pueden tener más de 100 caracteres.");
                    } else {
                        var medicationInfo = medicationText + " - Cantidad: " + quantity + ", Frecuencia: " + frequency + ", " +
                            (chronic ? "Crónica" : "Días: " + days);
                        var listItem = document.createElement("li");
                        listItem.textContent = medicationInfo;

                        document.getElementById("medicationList").appendChild(listItem);

                        // Limpiar campos después de añadir
                        document.getElementById("quantity").value = "";
                        document.getElementById("frequency").value = "";
                        document.getElementById("days").value = "";
                        document.getElementById("chronic").checked = false;
                    }
                }
</script>
            
            </fieldset>
            <label for="pdf">PDF:</label>
            <input type="file" id="pdf" name="pdf" accept=".pdf">
            
        <input type="submit" name="enviarConsulta" value="Pasar Consulta" />

        </fieldset>
    </form>
    <footer>
        <p>&copy; 2023 Ambulatorio Quintinilla. Todos los derechos reservados.</p>
    </footer>
</body>
</html>