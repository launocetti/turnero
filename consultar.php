<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultar Turno - Consultorio Médico</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; padding: 20px; }
        .container { max-width: 500px; margin: 0 auto; }
        h1 { color: white; text-align: center; margin-bottom: 30px; font-size: 2em; }
        .card { background: white; border-radius: 15px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); margin-bottom: 20px; }
        h2 { color: #333; margin-bottom: 20px; border-bottom: 2px solid #667eea; padding-bottom: 10px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; color: #555; font-weight: 600; }
        input { width: 100%; padding: 12px 15px; border: 2px solid #ddd; border-radius: 8px; font-size: 16px; }
        input:focus { outline: none; border-color: #667eea; }
        .btn { width: 100%; padding: 15px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 8px; font-size: 18px; font-weight: 600; cursor: pointer; }
        .btn:hover { transform: translateY(-2px); }
        .turno-item { padding: 20px; border: 2px solid #e0e0e0; border-radius: 10px; margin-bottom: 15px; }
        .turno-fecha { font-size: 18px; font-weight: bold; color: #333; margin-bottom: 10px; }
        .turno-detalles { color: #666; }
        .turno-estado { display: inline-block; padding: 5px 15px; border-radius: 20px; font-size: 14px; font-weight: bold; }
        .estado-confirmado { background: #d4edda; color: #155724; }
        .estado-atendido { background: #cce5ff; color: #004085; }
        .estado-cancelado { background: #f8d7da; color: #721c24; }
        .estado-no_asistio { background: #fff3cd; color: #856404; }
        .volver { display: block; text-align: center; color: white; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Consultar Turno</h1>
        
        <div class="card">
            <h2>Ingresá tu teléfono</h2>
            <form method="POST">
                <div class="form-group">
                    <label for="telefono">Teléfono:</label>
                    <input type="tel" id="telefono" name="telefono" required placeholder="Tu número de teléfono">
                </div>
                <button type="submit" class="btn">Buscar Turno</button>
            </form>
        </div>
        
        <?php
        require_once 'config.php';
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['telefono'])) {
            $conn = getDB();
            $telefono = $_POST['telefono'];
            
            $result = $conn->query("
                SELECT t.*, s.nombre as servicio_nombre, s.duracion, s.color, p.nombre as paciente_nombre 
                FROM turnos t
                LEFT JOIN servicios s ON t.servicio_id = s.id
                LEFT JOIN pacientes p ON t.paciente_id = p.id
                WHERE p.telefono = '$telefono'
                ORDER BY t.fecha DESC, t.hora DESC
                LIMIT 10
            ");
            
            if ($result && $result->num_rows > 0) {
                echo '<div class="card"><h2>📋 Tus Turnos</h2>';
                while ($turno = $result->fetch_assoc()) {
                    $estado_class = 'estado-' . $turno['estado'];
                    $fecha_formateada = date('d/m/Y', strtotime($turno['fecha']));
                    
                    echo "<div class='turno-item'>";
                    echo "<div class='turno-fecha'>📅 {$fecha_formateada} - ⏰ {$turno['hora']}</div>";
                    echo "<div class='turno-detalles'>";
                    echo "<strong>Servicio:</strong> {$turno['servicio_nombre']}<br>";
                    echo "<strong>Duración:</strong> {$turno['duracion']} minutos<br>";
                    if ($turno['motivo']) echo "<strong>Motivo:</strong> {$turno['motivo']}<br>";
                    echo "</div>";
                    echo "<div style='margin-top:10px;'>";
                    echo "<span class='turno-estado {$estado_class}'>{$turno['estado']}</span>";
                    echo "</div>";
                    echo "</div>";
                }
                echo '</div>';
            } else {
                echo '<div class="card"><p style="text-align:center;color:#666;">No se encontraron turnos con ese teléfono.</p></div>';
            }
            $conn->close();
        }
        ?>
        
        <a href="index.php" class="volver">← Volver a la página principal</a>
    </div>
</body>
</html>
