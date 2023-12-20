<?php
        $servidor = "localhost";
        $usuario = "root";
        $password = "root";

        $conexion = mysqli_connect($servidor,$usuario, $password);

        // Seleccionar la base de datos
        $usuarios = "ambulatorio";
        mysqli_select_db($conexion, $usuarios);

        if(!$conexion){
            echo "Conexion fallida";
        }else{
            echo "";
        }
?>