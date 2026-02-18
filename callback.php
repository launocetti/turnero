<?php
require_once 'config.php';
if (!isset($_GET['code'])) {
    die('Código de autorización no proporcionado');
}
$google = new GoogleCalendar();
if ($google->authenticate($_GET['code'])) {
    echo '<h1>✅ Google Calendar conectado exitosamente!</h1>';
    echo '<p>Ahora podés administrar tus turnos desde el panel de administración.</p>';
    echo '<a href="admin.php">Ir al panel de administración</a>';
} else {
    echo '<h1>❌ Error al conectar con Google Calendar</h1>';
    echo '<a href="admin.php">Volver</a>';
}
?>
