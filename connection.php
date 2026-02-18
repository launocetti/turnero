<?php
// Database connection
$host = "localhost";
$user = "root";
$password = "";
$database = "turnero";
$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
// Create tables if not exist
$sql = "CREATE TABLE IF NOT EXISTS servicios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    duracion INT NOT NULL,
    precio DECIMAL(10,2) NOT NULL
)";
$sql .= "CREATE TABLE IF NOT EXISTS turnos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_nombre VARCHAR(100) NOT NULL,
    cliente_telefono VARCHAR(20),
    cliente_email VARCHAR(100),
    servicio_id INT,
    fecha DATE NOT NULL,
    hora TIME NOT NULL,
    estado ENUM('confirmado', 'cancelado', 'completado') DEFAULT 'confirmado',
    FOREIGN KEY (servicio_id) REFERENCES servicios(id)
);";
$conn->multi_query($sql);
// Insert sample services if empty
$result = $conn->query("SELECT COUNT(*) as total FROM servicios");
$row = $result->fetch_assoc();
if ($row['total'] == 0) {
    $conn->query("INSERT INTO servicios (nombre, duracion, precio) VALUES 
        ('Consulta general', 30, 50.00),
        ('Servicio técnico', 60, 100.00),
        ('Asesoría', 45, 75.00)");
}
$conn->close();
?>
