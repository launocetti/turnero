<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Consultorio Médico</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; display: flex; justify-content: space-between; align-items: center; }
        .header h1 { font-size: 1.5em; }
        .header a { color: white; text-decoration: none; }
        .container { max-width: 1400px; margin: 20px auto; padding: 0 20px; }
        .card { background: white; border-radius: 10px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .card h2 { color: #333; margin-bottom: 20px; border-bottom: 2px solid #667eea; padding-bottom: 10px; }
        .btn { padding: 10px 20px; background: #667eea; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 14px; }
        .btn:hover { background: #5568d3; }
        .btn-success { background: #28a745; }
        .btn-danger { background: #dc3545; }
        .btn-warning { background: #ffc107; color: #333; }
        .btn-sm { padding: 5px 10px; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f8f9fa; font-weight: 600; }
        tr:hover { background: #f8f9fa; }
        .badge { display: inline-block; padding: 5px 10px; border-radius: 15px; font-size: 12px; font-weight: bold; }
        .badge-confirmado { background: #d4edda; color: #155724; }
        .badge-atendido { background: #cce5ff; color: #004085; }
        .badge-cancelado { background: #f8d7da; color: #721c24; }
        .badge-no_asistio { background: #fff3cd; color: #856404; }
        .filtros { display: flex; gap: 15px; margin-bottom: 20px; flex-wrap: wrap; }
        .filtros select, .filtros input { padding: 8px 12px; border: 1px solid #ddd; border-radius: 5px; }
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 20px; }
        .stat-card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center; }
        .stat-card h3 { font-size: 2em; color: #667eea; }
        .stat-card p { color: #666; }
        .google-status { display: flex; align-items: center; gap: 10px; }
        .status-connected { color: #28a745; }
        .status-disconnected { color: #dc3545; }
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); justify-content: center; align-items: center; }
        .modal.active { display: flex; }
        .modal-content { background: white; padding: 30px; border-radius: 10px; max-width: 500px; width: 90%; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: 600; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>🏥 Panel de Administración - Consultorio Médico</h1>
        <div>
            <?php
            require_once 'config.php';
            $google = new GoogleCalendar();
            if ($google->isAuthenticated()): ?>
                <span class="google-status status-connected">✅ Google Calendar Conectado</span>
            <?php else: ?>
                <a href="?conectar_google=1" class="btn">🔗 Conectar Google Calendar</a>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="container">
        <?php
        // Conectar Google Calendar
        if (isset($_GET['conectar_google'])) {
            $google = new GoogleCalendar();
            header('Location: ' . $google->getAuthUrl());
            exit;
        }
        
        // Procesar acciones
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $conn = getDB();
            
            if (isset($_POST['cambiar_estado'])) {
                $stmt = $conn->prepare("UPDATE turnos SET estado = ? WHERE id = ?");
                $stmt->bind_param("si", $_POST['nuevo_estado'], $_POST['turno_id']);
                $stmt->execute();
                
                // Si se cancela, eliminar de Google Calendar
                if ($_POST['nuevo_estado'] == 'cancelado') {
                    $result = $conn->query("SELECT google_event_id FROM turnos WHERE id = " . intval($_POST['turno_id']));
                    $row = $result->fetch_assoc();
                    if ($row['google_event_id']) {
                        $google->eliminarEvento($row['google_event_id']);
                    }
                }
            }
            
            if (isset($_POST['agregar_turno'])) {
                // Buscar o crear paciente
                $telefono = $_POST['telefono'];
                $result = $conn->query("SELECT id FROM pacientes WHERE telefono = '$telefono'");
                if ($row = $result->fetch_assoc()) {
                    $paciente_id = $row['id'];
                } else {
                    $conn->query("INSERT INTO pacientes (nombre, telefono, email) VALUES ('{$_POST['nombre']}', '$telefono', '{$_POST['email']}')");
                    $paciente_id = $conn->insert_id;
                }
                
                $stmt = $conn->prepare("INSERT INTO turnos (paciente_id, servicio_id, fecha, hora, duracion, motivo, estado) VALUES (?, ?, ?, ?, ?, ?, 'confirmado')");
                $stmt->bind_param("iissis", $paciente_id, $_POST['servicio_id'], $_POST['fecha'], $_POST['hora'], $_POST['duracion'], $_POST['motivo']);
                $stmt->execute();
                
                // Sincronizar con Google
                if ($google->isAuthenticated()) {
                    $result = $conn->query("SELECT s.nombre, s.duracion FROM servicios s WHERE s.id = " . intval($_POST['servicio_id']));
                    $servicio = $result->fetch_assoc();
                    $paciente = ['nombre' => $_POST['nombre'], 'telefono' => $telefono];
                    $turno_data = ['fecha' => $_POST['fecha'], 'hora' => $_POST['hora'], 'duracion' => $_POST['duracion'], 'motivo' => $_POST['motivo']];
                    $event = $google->crearEvento($turno_data, $paciente, $servicio);
                    if ($event) {
                        $conn->query("UPDATE turnos SET google_event_id = '" . $event->getId() . "' WHERE id = " . $conn->insert_id);
                    }
                }
            }
            
            if (isset($_POST['eliminar_turno'])) {
                $result = $conn->query("SELECT google_event_id FROM turnos WHERE id = " . intval($_POST['turno_id']));
                $row = $result->fetch_assoc();
                if ($row['google_event_id']) {
                    $google->eliminarEvento($row['google_event_id']);
                }
                $conn->query("DELETE FROM turnos WHERE id = " . intval($_POST['turno_id']));
            }
            
            $conn->close();
        }
        
        // Estadísticas
        $conn = getDB();
        $stats = [];
        $result = $conn->query("SELECT estado, COUNT(*) as total FROM turnos WHERE fecha = CURDATE() GROUP BY estado");
        while ($row = $result->fetch_assoc()) { $stats[$row['estado']] = $row['total']; }
        
        $total_hoy = array_sum($stats);
        $conn->close();
        ?>
        
        <div class="stats">
            <div class="stat-card">
                <h3><?php echo $total_hoy; ?></h3>
                <p>Turnos Hoy</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $stats['confirmado'] ?? 0; ?></h3>
                <p>Confirmados</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $stats['atendido'] ?? 0; ?></h3>
                <p>Atendidos</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $stats['cancelado'] ?? 0; ?></h3>
                <p>Cancelados</p>
            </div>
        </div>
        
        <div class="card">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
                <h2>📅 Turnos del Día</h2>
                <button class="btn" onclick="document.getElementById('modal-agregar').classList.add('active')">➕ Agregar Turno</button>
            </div>
            
            <div class="filtros">
                <select id="filtro-fecha" onchange="filtrarTurnos()">
                    <option value="">Todas las fechas</option>
                    <?php
                    $conn = getDB();
                    $result = $conn->query("SELECT DISTINCT fecha FROM turnos ORDER BY fecha DESC LIMIT 30");
                    while ($row = $result->fetch_assoc()) {
                        echo '<option value="' . $row['fecha'] . '">' . date('d/m/Y', strtotime($row['fecha'])) . '</option>';
                    }
                    $conn->close();
                    ?>
                </select>
                <select id="filtro-estado" onchange="filtrarTurnos()">
                    <option value="">Todos los estados</option>
                    <option value="confirmado">Confirmado</option>
                    <option value="atendido">Atendido</option>
                    <option value="cancelado">Cancelado</option>
                    <option value="no_asistio">No asistió</option>
                </select>
            </div>
            
            <table id="tabla-turnos">
                <thead>
                    <tr>
                        <th>Hora</th>
                        <th>Paciente</th>
                        <th>Teléfono</th>
                        <th>Servicio</th>
                        <th>Motivo</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $conn = getDB();
                    $filtro_fecha = $_GET['fecha'] ?? '';
                    $filtro_estado = $_GET['estado'] ?? '';
                    
                    $sql = "SELECT t.*, s.nombre as servicio_nombre, s.color, p.nombre as paciente_nombre, p.telefono 
                            FROM turnos t
                            LEFT JOIN servicios s ON t.servicio_id = s.id
                            LEFT JOIN pacientes p ON t.paciente_id = p.id
                            WHERE 1=1";
                    
                    if ($filtro_fecha) $sql .= " AND t.fecha = '$filtro_fecha'";
                    if ($filtro_estado) $sql .= " AND t.estado = '$filtro_estado'";
                    
                    $sql .= " ORDER BY t.fecha ASC, t.hora ASC";
                    
                    $result = $conn->query($sql);
                    while ($turno = $result->fetch_assoc()): ?>
                        <tr data-fecha="<?php echo $turno['fecha']; ?>" data-estado="<?php echo $turno['estado']; ?>">
                            <td><?php echo substr($turno['hora'], 0, 5); ?></td>
                            <td><?php echo htmlspecialchars($turno['paciente_nombre']); ?></td>
                            <td><?php echo htmlspecialchars($turno['telefono']); ?></td>
                            <td><span style="display:inline-block;width:12px;height:12px;background:<?php echo $turno['color']; ?>;border-radius:50%;margin-right:5px;"></span><?php echo $turno['servicio_nombre']; ?></td>
                            <td><?php echo htmlspecialchars($turno['motivo'] ?? '-'); ?></td>
                            <td><span class="badge badge-<?php echo $turno['estado']; ?>"><?php echo $turno['estado']; ?></span></td>
                            <td>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="turno_id" value="<?php echo $turno['id']; ?>">
                                    <?php if ($turno['estado'] == 'confirmado'): ?>
                                        <button type="submit" name="cambiar_estado" value="atendido" class="btn btn-success btn-sm">✓</button>
                                        <button type="submit" name="cambiar_estado" value="cancelado" class="btn btn-danger btn-sm">✗</button>
                                    <?php endif; ?>
                                    <button type="submit" name="eliminar_turno" value="1" class="btn btn-warning btn-sm" onclick="return confirm('¿Eliminar turno?')">🗑️</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; $conn->close(); ?>
                </tbody>
            </table>
        </div>
        
        <div class="card">
            <h2>📋 Gestión de Servicios</h2>
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Duración (min)</th>
                        <th>Color</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $conn = getDB();
                    $result = $conn->query("SELECT * FROM servicios");
                    while ($servicio = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $servicio['nombre']; ?></td>
                            <td><?php echo $servicio['duracion']; ?></td>
                            <td><span style="display:inline-block;width:20px;height:20px;background:<?php echo $servicio['color']; ?>;border-radius:50%;"></span></td>
                        </tr>
                    <?php endwhile; $conn->close(); ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Modal Agregar Turno -->
    <div id="modal-agregar" class="modal">
        <div class="modal-content">
            <h2>➕ Agregar Turno</h2>
            <form method="POST">
                <input type="hidden" name="agregar_turno" value="1">
                <div class="form-group">
                    <label>Nombre del paciente:</label>
                    <input type="text" name="nombre" required>
                </div>
                <div class="form-group">
                    <label>Teléfono:</label>
                    <input type="tel" name="telefono" required>
                </div>
                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" name="email">
                </div>
                <div class="form-group">
                    <label>Servicio:</label>
                    <select name="servicio_id" required>
                        <?php
                        $conn = getDB();
                        $result = $conn->query("SELECT * FROM servicios");
                        while ($s = $result->fetch_assoc()) {
                            echo "<option value='{$s['id']}'>{$s['nombre']} ({$s['duracion']} min)</option>";
                        }
                        $conn->close();
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Fecha:</label>
                    <input type="date" name="fecha" required min="<?php echo date('Y-m-d'); ?>">
                </div>
                <div class="form-group">
                    <label>Hora:</label>
                    <input type="time" name="hora" required>
                </div>
                <div class="form-group">
                    <label>Duración (minutos):</label>
                    <input type="number" name="duracion" value="30" min="15" step="15">
                </div>
                <div class="form-group">
                    <label>Motivo:</label>
                    <textarea name="motivo" rows="2"></textarea>
                </div>
                <button type="submit" class="btn">Guardar Turno</button>
                <button type="button" class="btn btn-warning" onclick="document.getElementById('modal-agregar').classList.remove('active')" style="margin-left:10px;">Cancelar</button>
            </form>
        </div>
    </div>
    
    <script>
        function filtrarTurnos() {
            const fecha = document.getElementById('filtro-fecha').value;
            const estado = document.getElementById('filtro-estado').value;
            const rows = document.querySelectorAll('#tabla-turnos tbody tr');
            
            rows.forEach(row => {
                const rowFecha = row.getAttribute('data-fecha');
                const rowEstado = row.getAttribute('data-estado');
                
                let mostrar = true;
                if (fecha && rowFecha !== fecha) mostrar = false;
                if (estado && rowEstado !== estado) mostrar = false;
                
                row.style.display = mostrar ? '' : 'none';
            });
        }
    </script>
</body>
</html>
