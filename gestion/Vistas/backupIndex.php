<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="container mt-4">

  <h2><i class="bi bi-hdd-stack"></i> Copias de Seguridad</h2>

  <?php if ($last): ?>
    <div class="alert alert-info mt-3">
      <i class="bi bi-clock-history"></i>
      Última copia realizada: <strong><?= htmlspecialchars($last) ?></strong>
    </div>
  <?php endif; ?>

  <div class="row mt-4">

    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Crear copia</h5>
          <a href="index.php?controller=BackupController&action=crear" class="btn btn-primary">Nueva copia</a>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Ver copias</h5>
          <a href="index.php?controller=BackupController&action=listar" class="btn btn-secondary">Listado</a>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Restaurar copia</h5>
          <a href="index.php?controller=BackupController&action=restaurar" class="btn btn-warning">Restaurar</a>
        </div>
      </div>
    </div>
  </div>

</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
