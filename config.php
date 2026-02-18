<?php
// ============================================
// CONFIGURACIÓN - COMPLETAR CON TUS DATOS
// ============================================
// Configuración de Base de Datos
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'turnero_medico');
// Configuración Google Calendar
// Obtener estas credenciales de Google Cloud Console
define('GOOGLE_CLIENT_ID', 'TU_CLIENT_ID.apps.googleusercontent.com');
define('GOOGLE_CLIENT_SECRET', 'TU_CLIENT_SECRET');
define('GOOGLE_REDIRECT_URI', 'http://localhost/turnero/callback.php');
// Token de acceso a Google Calendar (se genera automáticamente)
define('GOOGLE_ACCESS_TOKEN', '');
// Email del calendario de Google
define('CALENDAR_ID', 'TU_EMAIL@gmail.com');
// Configuración del consultorio
define('NOMBRE consultorio', 'Consultorio Médico');
define('DURACION_TURNO', 30); // minutos
define('HORARIO_INICIO', '09:00');
define('HORARIO_FIN', '18:00');
// Días de la semana disponibles (1=Lunes, 7=Domingo)
define('DIAS_DISPONIBLES', [1, 2, 3, 4, 5]); // Lunes a Viernes
// ============================================
// CONEXIÓN A BASE DE DATOS
// ============================================
function getDB() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $conn->set_charset("utf8mb4");
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }
    return $conn;
}
// ============================================
// INICIALIZAR BASE DE DATOS
// ============================================
function initDatabase() {
    $conn = getDB();
    
    $sql = "
    CREATE TABLE IF NOT EXISTS servicios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(100) NOT NULL,
        duracion INT NOT NULL DEFAULT 30,
        color VARCHAR(7) DEFAULT '#667eea'
    );
    
    CREATE TABLE IF NOT EXISTS pacientes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(100) NOT NULL,
        telefono VARCHAR(20) NOT NULL,
        email VARCHAR(100),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
    
    CREATE TABLE IF NOT EXISTS turnos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        paciente_id INT,
        servicio_id INT,
        fecha DATE NOT NULL,
        hora TIME NOT NULL,
        duracion INT DEFAULT 30,
        motivo TEXT,
        estado ENUM('confirmado', 'atendido', 'cancelado', 'no_asistio') DEFAULT 'confirmado',
        google_event_id VARCHAR(100),
        recordatorio_enviado TINYINT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (paciente_id) REFERENCES pacientes(id),
        FOREIGN KEY (servicio_id) REFERENCES servicios(id)
    );
    
    CREATE TABLE IF NOT EXISTS config (
        clave VARCHAR(50) PRIMARY KEY,
        valor TEXT
    );
    ";
    
    $conn->multi_query($sql);
    
    // Insertar servicios por defecto
    $result = $conn->query("SELECT COUNT(*) as total FROM servicios");
    $row = $result->fetch_assoc();
    if ($row['total'] == 0) {
        $conn->query("INSERT INTO servicios (nombre, duracion, color) VALUES 
            ('Consulta médica', 30, '#3498db'),
            ('Control', 15, '#2ecc71'),
            ('Ecografía', 45, '#9b59b6'),
            ('Curación', 30, '#e74c3c'),
            ('Certificado médico', 15, '#f39c12')");
    }
    
    $conn->close();
}
