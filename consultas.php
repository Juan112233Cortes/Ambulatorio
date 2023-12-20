<?php
    
require_once("conecta.php");


function obtenerMedicos($conexion) {
    // Consulta SELECT
    $sql = "SELECT * FROM medico";

    $resultado = mysqli_query($conexion, $sql);

    // Verificar si la consulta fue exitosa
    if ($resultado && mysqli_num_rows($resultado) > 0) {
        // Construir un array para almacenar los resultados
        $medicos = array();

        // Recorrer los resultados y almacenarlos en el array
        while ($fila = mysqli_fetch_assoc($resultado)) {
            // Almacena todas las columnas en el array
            $medicos[] = $fila;
        }

        // Devolver el array de resultados
        return $medicos;
    } else {
        // Devolver un mensaje indicando que no se encontraron resultados
        return "No se encontraron resultados.";
    }
}


function obtenerPacientes($conexion) {
    // Consulta SELECT
    $sql = "SELECT * FROM paciente";

    $resultado = mysqli_query($conexion, $sql);

    // Verificar si la consulta fue exitosa
    if ($resultado && mysqli_num_rows($resultado) > 0) {
        // Construir un array para almacenar los resultados
        $pacientes = array();

        // Recorrer los resultados y almacenarlos en el array
        while ($fila = mysqli_fetch_assoc($resultado)) {
            // Almacena todas las columnas en el array
            $pacientes[] = $fila;
        }

        // Devolver el array de resultados
        return $pacientes;
    } else {
        // Devolver un mensaje indicando que no se encontraron resultados
        return "No se encontraron resultados.";
    }
}



function obtenerConsultaMedico($conexion, $id_medico) {
    // Obtener la fecha actual
    $fecha_actual = date("Y-m-d");

    // Consulta SELECT con condición para obtener consultas de hoy
    $sql = "SELECT id_consulta, id_medico, id_paciente, fecha_consulta, sintomatologia FROM consulta WHERE id_medico = '$id_medico' AND fecha_consulta = '$fecha_actual'";

    $resultado = mysqli_query($conexion, $sql);

    if (!$resultado) {
        // Imprimir información detallada sobre el error SQL
        echo "Error en la consulta SQL: " . mysqli_error($conexion);
    }

    // Verificar si la consulta fue exitosa
    if ($resultado && mysqli_num_rows($resultado) > 0) {
        // Construir un array para almacenar los resultados
        $consulta = array();

        // Recorrer los resultados y almacenarlos en el array
        while ($fila = mysqli_fetch_assoc($resultado)) {
            // Almacena todas las columnas en el array
            $consulta[] = $fila;
        }

        // Devolver el array de resultados
        return $consulta;
    } else {
        // Devolver un mensaje indicando que no se encontraron resultados
        return "No se encontraron resultados para hoy.";
    }
}


function obtenerConsultasPaciente($conexion, $idPaciente) {
    // Consulta SELECT para obtener las consultas de un paciente
    $sql = "SELECT id_consulta, id_medico, fecha_consulta FROM consulta WHERE id_paciente = '$idPaciente'";

    $resultado = mysqli_query($conexion, $sql);

    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $consultas = array();

        while ($fila = mysqli_fetch_assoc($resultado)) {
            $consultas[] = $fila;
        }

        return $consultas;
    } else {
        return "No se encontraron consultas para el paciente seleccionado.";
    }
}

function obtenerMedicamentos($conexion) {
    // Consulta SELECT para obtener las consultas de un paciente
    $sql = "SELECT * FROM medicamento";

    $resultado = mysqli_query($conexion, $sql);

    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $medicamento = array();

        while ($fila = mysqli_fetch_assoc($resultado)) {
            $medicamento[] = $fila;
        }

        return $medicamento;
    } else {
        return "No se encontraron consultas para el paciente seleccionado.";
    }
}



?>