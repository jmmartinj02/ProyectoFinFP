<?php include __DIR__ . '/includes/header.php'; ?>

<div class="container mt-4">
  <h2><i class="bi bi-plus-circle"></i> Crear copia de seguridad</h2>

  <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?= $error ?></div>
  <?php endif; ?>

  <?php if (!empty($mensaje)): ?>
      <div class="alert alert-success"><?= $mensaje ?></div>
  <?php endif; ?>

  <form method="POST" action="index.php?controller=BackupController&action=generar">
    <div class="mb-3">
        <label class="form-label">Base de datos</label>
        <input type="text" class="form-control" name="db" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Tipo</label>
        <select class="form-select" name="tipo">
            <option value="full">Completo</option>
            <option value="incremental">Incremental</option>
            <option value="diferencial">Diferencial</option>
        </select>
    </div>

    <button class="btn btn-primary">Crear copia</button>
  </form>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
