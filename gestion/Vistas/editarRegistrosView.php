<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="container mt-4 mb-5">
  <h3 class="mb-3"><i class="bi bi-pencil-square"></i> Editar registro</h3>

  <form method="POST" action="index.php?controller=GestionController&action=actualizarRegistro">
    <input type="hidden" name="db" value="<?= htmlspecialchars($db) ?>">
    <input type="hidden" name="table" value="<?= htmlspecialchars($table) ?>">
    <input type="hidden" name="id" value="<?= htmlspecialchars($registro['id']) ?>">

    <?php foreach ($columnas as $col): 
        $nombre = $col['Field'];
        if ($nombre === 'id') continue;
    ?>
      <div class="mb-3">
        <label class="form-label"><?= htmlspecialchars($nombre) ?></label>
        <input type="text" name="<?= htmlspecialchars($nombre) ?>" 
               value="<?= htmlspecialchars($registro[$nombre] ?? '') ?>" 
               class="form-control" />
      </div>
    <?php endforeach; ?>

    <button type="submit" class="btn btn-success">Guardar cambios</button>
    <a href="index.php?controller=GestionController&action=ver&db=<?= urlencode($db) ?>&table=<?= urlencode($table) ?>" 
       class="btn btn-secondary">Cancelar</a>
  </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
