<?php
// Incluir el archivo de conexión
require_once("conecta.php");


if (!mysqli_query($conexion, "SHOW DATABASES LIKE 'ambulatorio'")->num_rows) {
    // CREATE DATABASE...
    mysqli_query($conexion, "CREATE DATABASE ambulatorio");

    // Seleccionar la base de datos recién creada
    mysqli_select_db($conexion, "ambulatorio");

    // Consulta SQL para crear la tabla Medico
    $sqlMedico = "CREATE TABLE medico (
        id_medico INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(255),
        apellidos VARCHAR(255),
        especialidad VARCHAR(255)
    )";
    
    // Consulta SQL para crear la tabla paciente
    $sqlPaciente = "CREATE TABLE paciente (
        id_paciente INT AUTO_INCREMENT PRIMARY KEY,
        dni VARCHAR(10) UNIQUE,
        nombre VARCHAR(255),
        apellidos VARCHAR(255),
        genero CHAR(1),
        fecha_nac DATE,
        id_medicos_asignados TEXT
    )";

    // Consulta SQL para crear la tabla medicamento
    $sqlMedicamento = "CREATE TABLE medicamento (
        id_medicamento INT AUTO_INCREMENT PRIMARY KEY,
        nombre_medicamento VARCHAR(255)
    )";

    // Crear la tabla "consulta"
    $sqlConsulta = "CREATE TABLE consulta (
        id_consulta INT AUTO_INCREMENT PRIMARY KEY,
        id_medico INT,
        id_paciente INT,
        fecha_consulta DATE,
        diagnostico VARCHAR(255),
        sintomatologia TEXT,
        pdf VARCHAR(255),
        FOREIGN KEY (id_medico) REFERENCES medico(id_medico),
        FOREIGN KEY (id_paciente) REFERENCES paciente(id_paciente)
    )";

    // Consulta SQL para crear la tabla receta
    $sqlReceta = "CREATE TABLE receta (
        id_receta INT AUTO_INCREMENT PRIMARY KEY,
        id_medicamento INT,
        id_consulta INT,
        posologia VARCHAR(255),
        fecha_fin DATE,
        FOREIGN KEY (id_medicamento) REFERENCES medicamento(id_medicamento),
        FOREIGN KEY (id_consulta) REFERENCES consulta(id_consulta)
    )";

    // Ejecutar las consultas
    mysqli_query($conexion, $sqlMedico);
    mysqli_query($conexion, $sqlPaciente);
    mysqli_query($conexion, $sqlMedicamento);
    mysqli_query($conexion, $sqlConsulta);
    mysqli_query($conexion, $sqlReceta);

    //inserts_paciente
    function insertarPaciente($conexion, $dni, $nombre, $apellidos, $genero, $fecha_nac, $id_medicos_asignados) {
        $sqlInsertPaciente = "INSERT INTO paciente(dni, nombre, apellidos, genero, fecha_nac, id_medicos_asignados) 
                              VALUES ('$dni', '$nombre', '$apellidos', '$genero', '$fecha_nac', '$id_medicos_asignados')";

        if (mysqli_query($conexion, $sqlInsertPaciente)) {
            echo "Datos insertados correctamente, en paciente.";
        } else {
            echo "Error al insertar datos, en pacientes: " . mysqli_error($conexion);
        }
    }

    // Insertar pacientes
    insertarPaciente($conexion, "123456789A", "Juan", "Pérez", "M", "2024-01-01", "1");
    insertarPaciente($conexion, "987654321B", "Maria", "Gomez", "F", "2024-01-02", "2");
    insertarPaciente($conexion, "555555555E", "Lucas", "Hernández", "M", "2024-12-20", "3");


    

    //inserts_medico
    function insertarMedico($conexion, $nombre, $apellidos, $especialidad) {
        $sqlInsertMedico = "INSERT INTO medico(nombre, apellidos, especialidad) 
                            VALUES ('$nombre', '$apellidos', '$especialidad')";
    
        if (mysqli_query($conexion, $sqlInsertMedico)) {
           echo "Datos insertados correctamente en Medico.<br>";
        } else {
           echo "Error al insertar datos en Medico: " . mysqli_error($conexion) . "<br>";
        }
    }

    // Insertar médicos
    insertarMedico($conexion, "Dr. Juan", "López", "Pediatría");
    insertarMedico($conexion, "Dra. Maria", "García", "Dermatología");
    insertarMedico($conexion, "Dr. Carlos", "Martínez", "Medicina de Familia");


    //Función para insertar medicamentos
    function insertarMedicamento($conexion, $nombre_medicamento){
        $sqlInsertMedicamento = "INSERT INTO medicamento(nombre_medicamento)
                                VALUES ('$nombre_medicamento')";

        if (mysqli_query($conexion, $sqlInsertMedicamento)) {
            echo "Datos insertados correctamente en medicamento.";
        } else {
           echo "Error al insertar datos en medicamento: " . mysqli_error($conexion);
        }
    }
    //Insertar medicamentos
    insertarMedicamento($conexion, "flutox");
    insertarMedicamento($conexion, "biodramina");
    insertarMedicamento($conexion, "voltadol");



    $ruta_carpeta_pdf = "pdf/";
    // Función para insertar consulta
    function insertarConsulta($conexion, $id_medico, $id_paciente, $fecha_consulta, $diagnostico, $sintomatologia,$pdf){
        $sqlInsertConsulta = "INSERT INTO consulta(id_medico, id_paciente, fecha_consulta, diagnostico, sintomatologia, pdf)    
                                VALUES ('$id_medico', '$id_paciente', '$fecha_consulta', '$diagnostico', '$sintomatologia', '$pdf')";

    if (mysqli_query($conexion, $sqlInsertConsulta)) {
        echo "Datos insertados correctamente en consulta.";
    } else {
        echo "Error al insertar datos en consulta: " . mysqli_error($conexion);
        }
    }

    
    // Insertar consultas
    insertarConsulta($conexion, 1, 1, "2023-01-01", "Gripe1", "Fiebre1, Tos1", "pdf/Diagnostico.pdf");
    insertarConsulta($conexion, 1, 1, "2023-11-30", "Gripe2", "Fiebre2, Tos2", $ruta_carpeta_pdf . "Diagnostico_copia(6).pdf");
    insertarConsulta($conexion, 1, 1, "2023-11-30", "Gripe3", "Fiebre3, Tos3", $ruta_carpeta_pdf . "Diagnostico_copia(7).pdf");
    insertarConsulta($conexion, 1, 1, "2023-12-07", "Gripe4", "Fiebre4, Tos4", $ruta_carpeta_pdf . "Diagnostico_copia(8).pdf");
    insertarConsulta($conexion, 2, 2, "2023-02-15", "Dolor de cabeza", "Mareos", $ruta_carpeta_pdf . "Diagnostico_copia(2).pdf");
    insertarConsulta($conexion, 2, 2, "2023-12-08", "Gripe", "Fiebre, Tos", $ruta_carpeta_pdf . "Diagnostico_copia(3).pdf");
    insertarConsulta($conexion, 1, 3, "2023-03-20", "Fatiga", "Dolor muscular", $ruta_carpeta_pdf . "Diagnostico_copia(4).pdf");
    insertarConsulta($conexion, 3, 3, "2023-12-09", "Gripe", "Fiebre, Tos", $ruta_carpeta_pdf . "Diagnostico_copia(5).pdf");
    


    // Función para insertar Recetas
    function insertarReceta($conexion, $id_medicamento, $id_consulta, $posologia, $fecha_fin){
        $sqlInsertReceta = "INSERT INTO receta(id_medicamento, id_consulta, posologia, fecha_fin)
                            VALUES ('$id_medicamento', '$id_consulta', '$posologia', '$fecha_fin')";

        if (mysqli_query($conexion, $sqlInsertReceta)) {
            echo "Datos insertados correctamente en receta.";
        } else {
            echo "Error al insertar datos en receta: " . mysqli_error($conexion);
        }
    }

    // Insertar recetas
    insertarReceta($conexion, 1, 1, "10 ml, 3 veces al día", "2024-12-31");
    insertarReceta($conexion, 2, 2, "Comprimidos de 50-100 mg, tomar cada 6h", "2024-01-20");
    insertarReceta($conexion, 3, 3, "aplicar 3 veces al día, si fuera necesario, aplicarlo más veces", "2024-02-15");


    }
?>
