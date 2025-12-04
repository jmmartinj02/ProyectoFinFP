<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="container mt-4">

  <h2><i class="bi bi-plus-circle"></i> Crear copia de seguridad</h2>

  <?php if (!empty($mensaje)): ?>
    <div class="alert alert-success mt-3"><?= $mensaje ?></div>
  <?php endif; ?>

  <?php if (!empty($error)): ?>
    <div class="alert alert-danger mt-3"><?= $error ?></div>
  <?php endif; ?>

  <form method="POST">

    <div class="mb-3">
      <label class="form-label">Base de datos</label>
      <select name="db" class="form-select" required>
        <option value="">-- elegir --</option>
        <?php foreach ($bases as $b): ?>
          <option value="<?= $b ?>"><?= $b ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">Tipo de copia</label>
      <select name="tipo" class="form-select">
        <option value="full">Completa</option>
        <option value="incremental">Incremental</option>
        <option value="diferencial">Diferencial</option>
      </select>
    </div>

    <button class="btn btn-primary">Crear copia</button>

  </form>

</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
