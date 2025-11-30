<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="container mt-4">

  <h2><i class="bi bi-arrow-counterclockwise"></i> Restaurar copia de seguridad</h2>

  <?php if (!empty($exito)): ?>
    <div class="alert alert-success"><?= $exito ?></div>
  <?php endif; ?>

  <?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= $error ?></div>
  <?php endif; ?>

  <form method="POST" action="index.php?controller=BackupController&action=restaurarBackup">

      <div class="mb-3">
        <label class="form-label fw-semibold">Selecciona un backup:</label>
        <select name="backup" class="form-select" required>
            <option value="">-- Selecciona un archivo --</option>

            <?php foreach ((new BackupModel())->listarBackups() as $b): ?>
              <option value="<?= htmlspecialchars($b) ?>"><?= htmlspecialchars($b) ?></option>
            <?php endforeach; ?>

        </select>
      </div>

      <button type="submit" class="btn btn-warning">
        <i class="bi bi-arrow-clockwise"></i> Restaurar
      </button>

      <a href="index.php?controller=BackupController&action=inicio" class="btn btn-secondary">
        Cancelar
      </a>
  </form>

</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
