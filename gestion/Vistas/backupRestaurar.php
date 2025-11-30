<?php include __DIR__ . '/includes/header.php'; ?>

<div class="container mt-4">

  <h2><i class="bi bi-arrow-counterclockwise"></i> Restaurar copia</h2>

  <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?= $error ?></div>
  <?php endif; ?>

  <?php if (!empty($mensaje)): ?>
      <div class="alert alert-success"><?= $mensaje ?></div>
  <?php endif; ?>

  <form method="POST" action="index.php?controller=BackupController&action=restaurar">

      <div class="mb-3">
          <label class="form-label fw-semibold">Selecciona archivo</label>
          <select class="form-select" name="file" required>
              <?php foreach ($files as $f): ?>
                  <option value="<?= $f ?>"><?= basename($f) ?></option>
              <?php endforeach; ?>
          </select>
      </div>

      <div class="mb-3">
          <label class="form-label">Base de datos destino</label>
          <input type="text" class="form-control" name="db" required>
      </div>

      <button class="btn btn-warning">Restaurar</button>
  </form>

</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
