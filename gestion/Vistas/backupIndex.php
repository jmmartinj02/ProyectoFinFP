<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="container mt-4">
  <h2><i class="bi bi-shield-lock"></i> Gestión de Copias de Seguridad</h2>
  <p class="text-muted">Desde aquí puedes generar nuevas copias, restaurarlas o gestionarlas.</p>

  <div class="row mt-4">
    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title"><i class="bi bi-plus-circle"></i> Crear copia</h5>
          <p class="card-text">Genera un backup completo, solo estructura o solo datos.</p>
          <a href="index.php?controller=BackupController&action=generar" class="btn btn-primary">Crear nueva copia</a>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title"><i class="bi bi-folder2-open"></i> Ver copias</h5>
          <p class="card-text">Lista de backups existentes para descargar o eliminar.</p>
          <a href="index.php?controller=BackupController&action=listar" class="btn btn-secondary">Ver copias</a>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title"><i class="bi bi-arrow-counterclockwise"></i> Restaurar copia</h5>
          <p class="card-text">Restaura una copia previamente creada.</p>
          <a href="index.php?controller=BackupController&action=restaurar" class="btn btn-warning">Restaurar</a>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
