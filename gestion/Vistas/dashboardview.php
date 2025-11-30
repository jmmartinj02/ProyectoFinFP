<?php include __DIR__ . '/../includes/header.php'; ?>

<style>
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
  .bar-container {
    margin: 0.6rem 0;
  }
  .bar-label {
    font-weight: 500;
    color: #333;
  }
  .bar {
    height: 18px;
    background-color: #3b6ef5;
    border-radius: 4px;
  }
  .bar-bg {
    background-color: #e9ecf5;
    border-radius: 4px;
    height: 18px;
    width: 100%;
  }
</style>

<div class="container mt-4 mb-5">
    <h2 class="mb-4"><i class="bi bi-speedometer2"></i> Panel de Control</h2>
    
    <?php if (!empty($lastBackup)): 
        $diffHours = (time() - strtotime($lastBackup)) / 3600;
        if ($diffHours >= 24): ?>
            <div class="alert alert-danger d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                Han pasado más de <strong>24 horas</strong> desde la última copia de seguridad.<br>
                Última copia: <strong><?= $lastBackup ?></strong>
                <a href="index.php?controller=BackupController&action=generar" class="btn btn-warning btn-sm ms-3">
                    Crear copia ahora
                </a>
            </div>
        <?php else: ?>
            <!-- Estado: Todo correcto -->
            <div class="alert alert-success d-flex align-items-center">
                <i class="bi bi-check-circle-fill me-2"></i>
                <div>
                    <strong>✅ Estado de copias de seguridad: CORRECTO</strong><br>
                    Última copia: <strong><?= $lastBackup ?></strong> 
                    (hace <?= number_format($diffHours, 1) ?> horas)
                    <?php if ($diffHours < 1): ?>
                        - <span class="text-muted">Reciente</span>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <?php if (empty($lastBackup)): ?>
        <div class="alert alert-danger d-flex align-items-center">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            ¡Aún no se ha realizado ninguna copia de seguridad!
            <a href="index.php?controller=BackupController&action=generar" class="btn btn-warning btn-sm ms-3">
                Crear primera copia
            </a>
        </div>
    <?php endif; ?>
</div>
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
                <?php foreach ($backupStats['lista'] as $b): ?>
                    <tr>
                        <td><?= htmlspecialchars($b['archivo']) ?></td>
                        <td><?= ucfirst($b['tipo']) ?></td>
                        <td><?= $b['fecha'] ?></td>
                        <td><?= round($b['tamano'] / 1024 / 1024, 2) ?> MB</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>


  <!-- Tarjetas resumen -->
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
  const chart = new Chart(ctx, {
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
    options: {
      responsive: true,
      scales: { y: { beginAtZero: true } }
    }
  });
  </script>
<?php else: ?>
  <p class="text-muted">No hay datos disponibles.</p>
<?php endif; ?>

  <div class="text-center mt-4">
    <a href="index.php?controller=GestionController&action=inicio" class="btn btn-primary">
      <i class="bi bi-database"></i> Ver bases de datos
    </a>
  </div>

</div>

  <div class="chart-box mt-4">
    <h5 class="mb-3"><i class="bi bi-list-check"></i> Detalle de bases de datos</h5>
    <?php if (!empty($detalle)): ?>
      <div class="table-responsive">
        <table class="table table-sm table-striped align-middle">
          <thead class="table-light">
            <tr>
              <th>Base de datos</th>
              <th>Tablas</th>
              <th>Registros</th>
              <th>Tamaño (MB)</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($detalle as $d): ?>
              <tr>
                <td><i class="bi bi-database"></i> <?= htmlspecialchars($d['nombre']) ?></td>
                <td><?= $d['tablas'] ?></td>
                <td><?= number_format($d['registros']) ?></td>
                <td><?= $d['tamano'] ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <p class="text-muted">No hay detalles disponibles.</p>
    <?php endif; ?>
  </div>

  <div class="text-center mt-4">
    <a href="index.php?controller=GestionController&action=inicio" class="btn btn-primary">
      <i class="bi bi-database"></i> Ver bases de datos
    </a>
  </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
