<?php
require_once("conecta.php");
function insertar_info_paciente($conexion){
    global $id_paciente;
    global $id_medico;
    
        // Obtener información del paciente
        $sqlPaciente = "SELECT nombre FROM paciente WHERE id_paciente = ?";
        $resultPaciente = $conexion->prepare($sqlPaciente);
        $resultPaciente->bind_param("i", $id_paciente);
        $resultPaciente->execute();
        $resultPaciente->bind_result($nombrePaciente);
        $resultPaciente->fetch();
        $resultPaciente->close(); // Cerrar el resultado antes de continuar
    
        if ($nombrePaciente) {
            echo "<b>Información del paciente:</b><br>";
            echo "-Nombre: " . $nombrePaciente . "<br><br>";
            echo "<b>Próximas citas:</b><br>";
    
            // Obtener citas pendientes del paciente
            $sqlCitas = "SELECT c.id_consulta, c.fecha_consulta, m.nombre, m.id_medico AS nombreMedico
                         FROM consulta c
                         JOIN medico m ON c.id_medico = m.id_medico
                         WHERE c.id_paciente = ? AND c.fecha_consulta > CURDATE()";
            $resultCitas = $conexion->prepare($sqlCitas);
            $resultCitas->bind_param("i", $id_paciente);
            $resultCitas->execute();
            $resultCitas->bind_result($idConsulta, $fechaConsulta, $nombreMedico,$id_medico);//Vincula las columnas utilizadas, a las variables
    
            if ($resultCitas->fetch()) {
                $numCOnsulta = 1;
                do {
                    echo "Consulta" . $numCOnsulta;
                    echo "<table border='1'>";
                    echo "<tr><td colspan='2'><b>Información de la cita:</b></td></tr>";
                    echo "<tr><td>Fecha:</td><td>" . $fechaConsulta . "</td></tr>";
                    echo "<tr><td>Id de la consulta:</td><td>" . $idConsulta . "</td></tr>";
                    echo "<tr><td>Id de la medico:</td><td>" . $id_medico . "</td></tr>";


                    echo "<tr><td colspan='2'><b>Información del médico:</b></td></tr>";
                    echo "<tr><td>Nombre del médico:</td><td>" . $nombreMedico . "</td></tr>";
                    echo "</table>";
                    echo "<br>";
                    $numCOnsulta ++;
                     // Línea divisoria entre resultados
                } while ($resultCitas->fetch());
                
            } else {
                echo "No hay citas pendientes para este paciente.";
            }
            $resultCitas->close(); // Cerrar el resultado después de usarlo
            } else {
                echo "Paciente no encontrado.";
            }
            
    }
    
    
function insertar_info_medicacion($conexion,$id_paciente) {
        if (isset($_POST['verPaciente'])) {
            $id_paciente = mysqli_real_escape_string($conexion, $_POST["id_paciente"]);
    
            // Obtener información del paciente
            $sqlPaciente = "SELECT nombre FROM paciente WHERE id_paciente = ?";
            $resultPaciente = $conexion->prepare($sqlPaciente);
            $resultPaciente->bind_param("i", $id_paciente);//vincula parametros, i=inter, s=string, d=double, se vincula a $id_paciente
            $resultPaciente->execute();
            $resultPaciente->bind_result($nombrePaciente);
            $resultPaciente->fetch();
            $resultPaciente->close(); // Cerrar el resultado antes de continuar
    
            if ($nombrePaciente) {
    
                // Obtener citas pendientes del paciente con la condición de fecha_consulta < CURDATE()
                $sqlMedicacion = "SELECT r.id_receta, m.nombre_medicamento, r.posologia, r.fecha_fin
                        FROM receta r
                        JOIN medicamento m ON r.id_medicamento = m.id_medicamento
                        WHERE r.id_consulta IN 
                            (SELECT c.id_consulta FROM consulta c WHERE c.id_paciente = ? AND c.fecha_consulta < CURDATE())";
    
                $resultCita = $conexion->prepare($sqlMedicacion);
                $resultCita->bind_param("i", $id_paciente);
                $resultCita->execute();
                $resultCita->bind_result($id_receta, $nombre_medicamento, $posologia, $fecha_fin);//Vincula las columnas utilizadas, a las variables
    
                if ($resultCita->fetch()) {
                    do {
                        echo "<br><b>Medicación actual: </b><br>";
                        echo "<table border='1'>";
                        echo "<tr><td>Nombre del medicamento</td><td>" . $nombre_medicamento . "</td></tr>";
                        echo "<tr><td>Fecha fin de tratamiento</td><td>" . $fecha_fin . "</td></tr>";
                        echo "</table>";
                        
                        echo "<br>Información de la receta:<br>";
                        echo "<table border='1'>";
                        echo "<tr><td>Receta</td><td>" . $posologia . "</td></tr>";
                        echo "</table>";
                        echo "<hr>"; // Línea divisoria entre resultados
                    } while ($resultCita->fetch());
                } else {
                    echo "No hay medicación para este paciente.";
                }
                $resultCita->close(); // Cerrar el resultado después de usarlo
            } else {
                echo "Paciente no encontrado.";
            }
        }
    }
    
function insertar_consultas_pasadas($conexion,$id_paciente){
    global $id_paciente;
    
        // Obtener información del paciente
        $sqlPaciente = "SELECT nombre FROM paciente WHERE id_paciente = ?";
        $resultPaciente = $conexion->prepare($sqlPaciente);
        $resultPaciente->bind_param("i", $id_paciente);
        $resultPaciente->execute();
        $resultPaciente->bind_result($nombrePaciente);
        $resultPaciente->fetch();
        $resultPaciente->close(); // Cerrar el resultado antes de continuar
    
        if ($nombrePaciente) {
            echo "<hr>";
            echo "<b>Citas Pasadas: </b><br>";
            echo "Paciente: " . $nombrePaciente . "<br>";
    
            // Obtener citas pendientes del paciente
            $sqlCitas = "SELECT c.id_consulta, c.fecha_consulta
                         FROM consulta c
                         WHERE c.id_paciente = ? AND c.fecha_consulta < CURDATE()";
            $resultCitas = $conexion->prepare($sqlCitas);
            $resultCitas->bind_param("i", $id_paciente);
            $resultCitas->execute();
            $resultCitas->bind_result($idConsulta, $fecha_fin);//Vincula las columnas utilizadas, a las variables
    
            if ($resultCitas->fetch()) {
                $numCitas = 1;
                do {
                    echo "Cita". $numCitas;
                    echo "<table border='1'>";
                    echo "<tr><td colspan='2'><b>Información de la cita:</b></td></tr>";
                    echo "<tr><td>Fecha:</td><td>" . $fecha_fin . "</td></tr>";
                    echo "<tr><td>Id de la consulta:</td><td>" . $idConsulta . "</td></tr>";
                    echo "</table>";

                    $numCitas ++;
                } while ($resultCitas->fetch());
                
            } else {
                echo "No hay citas pendientes para este paciente.";
            }
            $resultCitas->close(); // Cerrar el resultado después de usarlo
            } else {
                echo "Paciente no encontrado.";
            }
            
    }


function insertar_consultas_pasadas_info($conexion,$id_paciente){
        global $id_paciente;
        
            // Obtener información del paciente
            $sqlPaciente = "SELECT nombre FROM paciente WHERE id_paciente = ?";
            $resultPaciente = $conexion->prepare($sqlPaciente);
            $resultPaciente->bind_param("i", $id_paciente);
            $resultPaciente->execute();
            $resultPaciente->bind_result($nombrePaciente);
            $resultPaciente->fetch();
            $resultPaciente->close(); // Cerrar el resultado antes de continuar
        
            if ($nombrePaciente) {
                echo "<hr>";
                echo "<b>Información Citas Pasadas: </b><br>";
                echo "Paciente: " . $nombrePaciente . "<br>";
        
                // Obtener citas pendientes del paciente info
                $sqlCitas = "SELECT med.nombre, c.fecha_consulta, c.diagnostico, c.sintomatologia, m.nombre_medicamento, c.pdf
                             FROM consulta c
                             JOIN receta r ON c.id_consulta = r.id_consulta
                             JOIN medicamento m ON r.id_medicamento = m.id_medicamento
                             JOIN medico med ON c.id_medico = med.id_medico
                             WHERE c.id_paciente = ? AND c.fecha_consulta < CURDATE()";
                $resultCitas = $conexion->prepare($sqlCitas);
                $resultCitas->bind_param("i", $id_paciente);
                $resultCitas->execute();
                $resultCitas->bind_result($nombreMedico, $fecha_consulta, $diagnostico, $sintomatologia,$nombre_medicamento,$pdf);//Vincula las columnas utilizadas, a las variables
        
                echo "<table border='1'>";
                echo "<tr><th>Nombre del Paciente</th><th>Fecha</th><th>Nombre del Médico</th><th>Diagnóstico</th><th>Nombre del Medicamento</th><th>PDF</th></tr>";

                if ($resultCitas->fetch()) {
                   do {
                        echo "<tr>";
                    
                      echo "<td>" . $nombrePaciente . "</td>";
                      echo "<td>" . $fecha_consulta . "</td>";
                      echo "<td>" . $nombreMedico . "</td>";
                      echo "<td>" . $diagnostico . "</td>";
                      echo "<td>" . $nombre_medicamento . "</td>";
                      echo "<td>" . $pdf . "</td>";
                      echo "</tr>";

                      // Línea divisoria entre resultados
                      echo "<tr><td colspan='6'></td></tr>";

                  } while ($resultCitas->fetch());
                } else {
                    echo "<tr><td colspan='7'>No hay citas pendientes para este paciente.</td></tr>";
                }

                echo "</table>";

                $resultCitas->close(); // Cerrar el resultado después de usarlo
                } else {
                    echo "<p>Paciente no encontrado.</p>";
                }

                
            
                
        }

function insertar_pfd($conexion, $id_paciente)
        {
            global $id_paciente;
        
            // Se verifica si se ha enviado la solicitud para ver el PDF
            if (isset($_POST['verPDF'])) {
                // Ruta a la carpeta que contiene los archivos PDF (debe ser configurada según tu estructura)
                $ruta_carpeta_pdf = "";
        
                // Se recupera la ruta del archivo PDF desde la base de datos según el paciente seleccionado
                $query = "SELECT pdf FROM consulta WHERE id_paciente = $id_paciente LIMIT 1";
                $result = mysqli_query($conexion, $query);
        
                if ($result && mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    $pdf_path = $ruta_carpeta_pdf . $row['pdf'];
        
                    // Se verifica si el archivo existe antes de intentar mostrarlo
                    if (file_exists($pdf_path)) {
                        // Muestra el PDF en un iframe
                        echo "<iframe src='./$pdf_path' width='100%' height='600px'></iframe>";
                    } else {
                        echo "PDF file not found.";
                    }
                } else {
                    echo "No PDF file associated with the selected patient.";
                }
            } else {
                echo "Invalid request.";
            }
        }
        
        
function obtenerConsultasProximos7Dias($conexion, $id_medico) {
            // Obtiene la fecha actual en el formato 'Y-m-d' (año-mes-día)
            $fechaActual = date('Y-m-d');

            // Obtiene la fecha actual y le suma 7 días utilizando strtotime, luego la formatea en 'Y-m-d'
            $fecha7Dias = date('Y-m-d', strtotime('+7 days'));

        
            $sql = "SELECT c.id_consulta, p.nombre, p.id_paciente as nombre_paciente, c.sintomatologia
                    FROM consulta c
                    INNER JOIN paciente p ON c.id_paciente = p.id_paciente
                    WHERE c.id_medico = ? AND c.fecha_consulta BETWEEN ? AND ?";
        
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("iss", $id_medico, $fechaActual, $fecha7Dias);
            $stmt->execute();
            $stmt->bind_result($idConsulta, $nombrePaciente, $sintomatologia, $id_paciente);
        
            echo "<b>Consultas en los próximos 7 días:</b><br>";
        
            // Crear la tabla HTML
            echo "<table border='1'>";
            echo "<tr><th>ID de Cita</th><th>Nombre del Paciente</th><th>ID Paciente</th><th>Sintomatología</th></tr>";
        
            while ($stmt->fetch()) {
                echo "<tr>";
                echo "<td>" . $idConsulta . "</td>";
                echo "<td>" . $nombrePaciente . "</td>";
                
                echo "<td>" . ($sintomatologia ? $sintomatologia : "No disponible") . "</td>";
                echo "<td>" . $id_paciente . "</td>";
                echo "</tr>";
            }

            echo "</table>";

            $consultas = []; // Crear un array para almacenar los resultados

            while ($stmt->fetch()) {
                $consulta = [
                    'id_consulta' => $idConsulta,
                    'nombre_paciente' => $nombrePaciente,
                    'sintomatologia' => $sintomatologia,
                    'id_paciente' => $id_paciente,
                ];
            
                $consultas[] = $consulta;
            }
        
            
        
            $stmt->close();
        }
        
        
function obtenerConsultasHoy($conexion, $id_medico) {
            // Obtiene la fecha actual en el formato 'Y-m-d' (año-mes-día)
            $fechaHoy = date('Y-m-d');
        
            $sql = "SELECT c.id_consulta, p.nombre, p.id_paciente as nombre_paciente, c.sintomatologia
                    FROM consulta c
                    INNER JOIN paciente p ON c.id_paciente = p.id_paciente
                    WHERE c.id_medico = ? AND c.fecha_consulta = ?";
            
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("is", $id_medico, $fechaHoy);
            $stmt->execute();
            $stmt->bind_result($idConsulta, $nombrePaciente, $sintomatologia, $id_paciente);
            
            echo "<b>Consultas de hoy:</b><br>";
        
            echo "<table border='1'>";
            echo "<tr><th>ID de Cita</th><th>Nombre del Paciente</th><th>ID Paciente</th><th>Sintomatología</th></tr>";
        
            while ($stmt->fetch()) {
                echo "<tr>";
                echo "<td>" . $idConsulta . "</td>";
                echo "<td>" . $nombrePaciente . "</td>";
                
                echo "<td>" . ($sintomatologia ? $sintomatologia : "No disponible") . "</td>";
                echo "<td>" . $id_paciente . "</td>";
                echo "</tr>";
            }
        
            echo "</table>";
            $stmt->close();
        }

function obtenerIdMedicoExistente($conexion) {
            $query = "SELECT id_medico FROM medico";
            $result = mysqli_query($conexion, $query);
        
            $idMedicos = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $idMedicos[] = $row['id_medico'];
            }
        
            return $idMedicos;
        }
        
function obtenerIdPacienteExistente($conexion) {
            $query = "SELECT id_paciente FROM paciente";
            $result = mysqli_query($conexion, $query);
        
            $idPacientes = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $idPacientes[] = $row['id_paciente'];
            }
        
            return $idPacientes;
        }
        
function obtenerSintomatologiaExistente($conexion) {
            $query = "SELECT sintomatologia FROM consulta";
            $result = mysqli_query($conexion, $query);
        
            $sintomatologias = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $sintomatologias[] = $row['sintomatologia'];
            }
        
            return $sintomatologias;
        }

function obtenerConsultasPasadas($conexion, $id_paciente, $fecha_actual = null) {
            // Si no se proporciona una fecha, se establece la fecha actual por defecto
            if ($fecha_actual === null) {
                $fecha_actual = date("Y-m-d");
            }
        
            // Consulta SQL para obtener las consultas médicas pasadas del paciente
            $query = "SELECT * FROM consulta WHERE id_paciente = ? AND fecha_consulta <= ?";
            
            // Preparación de la consulta
            $statement = mysqli_prepare($conexion, $query);
        
            // Vincula los parámetros a la consulta preparada
            mysqli_stmt_bind_param($statement, "is", $id_paciente, $fecha_actual);
        
            // Ejecuta la consulta
            mysqli_stmt_execute($statement);
        
            // Obtiene el resultado de la consulta
            $result = mysqli_stmt_get_result($statement);
        
            // Almacena las consultas en un array
            $consultas = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $consultas[] = $row;
            }
        
            // Cierra la consulta preparada
            mysqli_stmt_close($statement);
        
            // Devuelve el array de consultas
            return $consultas;
        }
        

?>