<?php include __DIR__ . '/../includes/header.php'; ?>

<style>
/* --- ESTILOS DEL DASHBOARD --- */
.bar-label a,
.bar-label {
    color: #2648c0;
    cursor: pointer;
    transition: color 0.2s ease;
}
.bar-label a:hover,
.bar-label:hover {
    color: #1a35a8;
    text-decoration: underline;
}
.stats-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 1rem;
}
.card-stat {
    background: #fff;
    border: 1px solid #e3e3e3;
    border-radius: 10px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    padding: 1.2rem;
    text-align: center;
}
.card-stat i {
    font-size: 2rem;
}
.chart-box {
    background: #fff;
    border: 1px solid #e3e3e3;
    border-radius: 10px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    padding: 1.5rem;
    margin-top: 2rem;
}
</style>

<div class="container mt-4 mb-5">
    <h2 class="mb-4"><i class="bi bi-speedometer2"></i> Panel de Control</h2>

    <!-- ======================= ALERTA DE BACKUP ======================= -->
    <?php if (!empty($lastBackup)): 
        $diffHours = (time() - strtotime($lastBackup)) / 3600;
    ?>
        <?php if ($diffHours >= 24): ?>
            <div class="alert alert-danger d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <div>
                    Han pasado más de <strong>24 horas</strong> desde la última copia.
                    <br>Última copia: <strong><?= htmlspecialchars($lastBackup) ?></strong>
                </div>
                <a href="index.php?controller=BackupController&action=generar" 
                   class="btn btn-warning btn-sm ms-3">
                    Crear copia ahora
                </a>
            </div>
        <?php else: ?>
            <div class="alert alert-success d-flex align-items-center">
                <i class="bi bi-check-circle-fill me-2"></i>
                <div>
                    <strong>Estado de copias: CORRECTO</strong><br>
                    Última copia: <strong><?= htmlspecialchars($lastBackup) ?></strong>
                    (hace <?= number_format($diffHours, 1) ?> horas)
                </div>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="alert alert-danger d-flex align-items-center">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            ¡Aún no se ha realizado ninguna copia de seguridad!
            <a href="index.php?controller=BackupController&action=generar" 
               class="btn btn-warning btn-sm ms-3">
                Crear primera copia
            </a>
        </div>
    <?php endif; ?>

    <!-- ======================= ESTADÍSTICAS DE BACKUP ======================= -->
    <?php if (!empty($backupStats)): ?>
    <div class="chart-box mt-4">
        <h5><i class="bi bi-hdd-stack"></i> Estado de copias de seguridad</h5>

        <div class="row mt-3">
            <div class="col-md-3">
                <div class="card-stat">
                    <i class="bi bi-archive text-primary"></i>
                    <h5><?= $backupStats['total'] ?></h5>
                    <p class="text-muted mb-0">Copias totales</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card-stat">
                    <i class="bi bi-hdd text-success"></i>
                    <h5><?= $backupStats['porTipo']['full'] ?></h5>
                    <p class="text-muted mb-0">Full</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card-stat">
                    <i class="bi bi-node-plus-fill text-warning"></i>
                    <h5><?= $backupStats['porTipo']['incremental'] ?></h5>
                    <p class="text-muted mb-0">Incremental</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card-stat">
                    <i class="bi bi-diagram-2 text-danger"></i>
                    <h5><?= $backupStats['porTipo']['diferencial'] ?></h5>
                    <p class="text-muted mb-0">Diferencial</p>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <p><strong>Tamaño total ocupado:</strong> <?= $backupStats['tamano'] ?> MB</p>
        </div>

        <h6 class="mt-4">Últimas 5 copias</h6>
        <div class="table-responsive">
            <table class="table table-sm table-striped">
                <thead class="table-light">
                    <tr>
                        <th>Archivo</th>
                        <th>Tipo</th>
                        <th>Fecha</th>
                        <th>Tamaño</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($backupStats['lista'])): ?>
                    <?php foreach ($backupStats['lista'] as $b): ?>
                        <tr>
                            <td><?= htmlspecialchars($b['archivo']) ?></td>
                            <td><?= ucfirst(htmlspecialchars($b['tipo'])) ?></td>
                            <td><?= date("Y-m-d H:i:s", $b['fecha']) ?></td>
                            <td><?= $b['tamano'] ?> MB</td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4" class="text-muted">No hay copias recientes.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
    <!-- ======================= ESTADÍSTICAS DE LOGS ======================= -->
  </div>
  <?php if (!empty($ultimosLogs)): ?>
  <div class="chart-box mt-4">
      <h5><i class="bi bi-clipboard-check"></i> Últimos eventos del sistema</h5>

      <div class="table-responsive">
          <table class="table table-sm table-striped align-middle">
              <thead class="table-light">
                  <tr>
                      <th>Fecha</th>
                      <th>Usuario</th>
                      <th>Acción</th>
                      <th>Base de datos</th>
                      <th>Tabla</th>
                      <th>Detalles</th>
                  </tr>
              </thead>
              <tbody>
                  <?php foreach ($ultimosLogs as $log): ?>
                  <tr>
                      <td><?= htmlspecialchars($log['fecha']) ?></td>
                      <td><?= htmlspecialchars($log['usuario'] ?? 'N/A') ?></td>
                      <td><span class="badge bg-primary"><?= htmlspecialchars($log['accion']) ?></span></td>
                      <td><?= htmlspecialchars($log['base_datos'] ?? '-') ?></td>
                      <td><?= htmlspecialchars($log['tabla'] ?? '-') ?></td>
                      <td class="text-muted" style="max-width:250px;"><?= htmlspecialchars($log['detalle'] ?? '') ?></td>
                  </tr>
                  <?php endforeach; ?>
              </tbody>
          </table>
      </div>

      <div class="text-end mt-2">
          <a href="index.php?controller=LogsController&action=index" class="btn btn-outline-primary btn-sm">
              Ver todos los logs
          </a>
      </div>
  </div>
  <?php else: ?>
  <div class="chart-box mt-4">
      <h5><i class="bi bi-clipboard-check"></i> Últimos eventos del sistema</h5>
      <p class="text-muted">Aún no hay registros en los logs.</p>
  </div>
  <?php endif; ?>


<!-- ======================= ESTADÍSTICAS DE BBDD ======================= -->

<div class="stats-cards">
    <div class="card-stat">
        <i class="bi bi-hdd-network text-primary"></i>
        <h5 class="mt-2"><?= $resumen['bases'] ?></h5>
        <p class="text-muted mb-0">Bases de datos</p>
    </div>

    <div class="card-stat">
        <i class="bi bi-table text-success"></i>
        <h5 class="mt-2"><?= $resumen['tablas'] ?></h5>
        <p class="text-muted mb-0">Tablas</p>
    </div>

    <div class="card-stat">
        <i class="bi bi-bar-chart text-warning"></i>
        <h5 class="mt-2"><?= $resumen['registros'] ?></h5>
        <p class="text-muted mb-0">Registros estimados</p>
    </div>

    <div class="card-stat">
        <i class="bi bi-pie-chart text-danger"></i>
        <h5 class="mt-2"><?= $resumen['tamano'] ?> MB</h5>
        <p class="text-muted mb-0">Tamaño total</p>
    </div>
</div>

<div class="chart-box">
    <h5 class="mb-3"><i class="bi bi-graph-up"></i> Tablas por base de datos</h5>
    <canvas id="chartTablas" height="100"></canvas>
</div>

<?php if (!empty($graficos)): ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('chartTablas').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_keys($graficos)) ?>,
        datasets: [{
            label: 'Número de tablas',
            data: <?= json_encode(array_values($graficos)) ?>,
            backgroundColor: 'rgba(59, 110, 245, 0.6)',
            borderColor: 'rgba(59, 110, 245, 1)',
            borderWidth: 1
        }]
    },
    options: { responsive: true, scales: { y: { beginAtZero: true } } }
});
</script>
<?php else: ?>
<p class="text-muted">No hay datos disponibles.</p>
<?php endif; ?>


<?php include __DIR__ . '/../includes/footer.php'; ?>
