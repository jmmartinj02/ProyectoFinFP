<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="container mt-4">
  <h3><i class="bi bi-plus-circle"></i> Insertar nuevo registro en <?= htmlspecialchars($table) ?></h3>
  <p class="text-muted">Introduce los datos para los campos de la tabla.</p>

  <form action="index.php?controller=GestionController&action=insertarRegistro&db=<?= urlencode($dbName) ?>&table=<?= urlencode($table) ?>" method="POST">

    <?php foreach ($columnas as $col): ?>
      <?php
        // Saltamos claves auto_increment (normalmente ID)
        if (strpos($col['Extra'], 'auto_increment') !== false) continue;
      ?>
      <div class="mb-3">
        <label for="<?= htmlspecialchars($col['Field']) ?>" class="form-label">
          <?= htmlspecialchars($col['Field']) ?> (<?= htmlspecialchars($col['Type']) ?>)
        </label>
        <input type="text" 
               class="form-control" 
               name="<?= htmlspecialchars($col['Field']) ?>" 
               id="<?= htmlspecialchars($col['Field']) ?>" 
               <?= ($col['Null'] === 'NO') ? 'required' : '' ?>>
      </div>
    <?php endforeach; ?>

    <button type="submit" class="btn btn-primary">Guardar registro</button>
    <a href="index.php?controller=GestionController&action=ver&db=<?= urlencode($dbName) ?>&table=<?= urlencode($table) ?>" 
       class="btn btn-outline-secondary">Cancelar</a>
  </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
