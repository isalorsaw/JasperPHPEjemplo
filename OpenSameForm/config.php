<?php
// Datos de conexión
$_SESSION['servidor'] = "";
$_SESSION['usuario']   = "";
$_SESSION['clave']     = "";
$_SESSION['bd']        = "";

// Intentar conexión
$conexion = mysqli_connect(
    $_SESSION['servidor'],
    $_SESSION['usuario'],
    $_SESSION['clave'],
    $_SESSION['bd']
);

// Verificar conexión
if (!$conexion) {
    echo "Error: No se pudo conectar a MySQL." . PHP_EOL;
    echo "errno de depuración: " . mysqli_connect_errno() . PHP_EOL;
    echo "error de depuración: " . mysqli_connect_error() . PHP_EOL;
    exit;
} else {
    // Establecer charset
    mysqli_set_charset($conexion, "utf8");
    //echo "Conexión establecida correctamente.";
}
?>