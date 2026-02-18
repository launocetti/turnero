<?php
// Process reservation
if (isset($_POST['reservar'])) {
    $conn = new mysqli($host, $user, $password, $database);
    
    $stmt = $conn->prepare("INSERT INTO turnos (cliente_nombre, cliente_telefono, cliente_email, servicio_id, fecha, hora) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssiss", 
        $_POST['nombre'],
        $_POST['telefono'],
        $_POST['email'],
        $_POST['servicio_id'],
        $_POST['fecha'],
        $_POST['hora']
    );
    
    if ($stmt->execute()) {
        $mensaje = "Tu turno ha sido reservado correctamente. Te enviaremos un confirmación.";
        $mensaje_tipo = "mensaje-exito";
    } else {
        $mensaje = "Error al reservar el turno. Intenta nuevamente.";
        $mensaje_tipo = "mensaje-error";
    }
    
    $conn->close();
}
?>
