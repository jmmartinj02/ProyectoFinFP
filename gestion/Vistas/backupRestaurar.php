<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="container mt-4">

  <h2><i class="bi bi-arrow-counterclockwise"></i> Restaurar copia</h2>

  <?php if (!empty($error)): ?>
      <div class="alert alert-danger mt-3"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="POST">

    <div class="mb-3">
      <label class="form-label">Selecciona copia</label>
      <select name="file" class="form-select" required>
        <option value="">-- seleccionar --</option>
        <?php foreach ($files as $path): ?>
          <option value="<?= basename($path) ?>"><?= basename($path) ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="form-check mb-3">
      <input class="form-check-input" type="checkbox" id="autoCreate" name="autoCreate" value="1">
      <label class="form-check-label" for="autoCreate">
        Crear base de datos automáticamente según el nombre del archivo
      </label>
    </div>

    <div id="manualDB" class="mb-3">
      <label class="form-label">Base de datos destino</label>
      <select name="db" class="form-select">
        <option value="">-- elegir --</option>
        <?php foreach ($bases as $b): ?>
          <option value="<?= $b ?>"><?= $b ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <button class="btn btn-warning">Restaurar</button>

  </form>

</div>

<script>
document.getElementById('autoCreate').addEventListener('change', function() {
    document.getElementById('manualDB').style.display = this.checked ? 'none' : 'block';
});
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
