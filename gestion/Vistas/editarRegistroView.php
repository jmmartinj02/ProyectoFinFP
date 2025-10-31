<?php include __DIR__ . '/../includes/header.php'; ?>
  <!-- volver a los registros -->
<div class="container mt-4 mb-5">
  <a href="index.php?controller=GestionController&action=ver&db=<?= urlencode($db ?? '') ?>&table=<?= urlencode($table ?? '') ?>" 
     class="btn btn-outline-primary ms-auto">
    <i class="bi bi-arrow-left-circle"></i> Volver a los registros
  </a>
  <h3 class="mb-3"><i class="bi bi-pencil-square"></i> Editar registro</h3>

  <!-- Información contextual del registro -->
  <h6 class="text-muted mb-4">
    Editando registro de <strong><?= htmlspecialchars($table ?? 'tabla desconocida') ?></strong>
    (<?= htmlspecialchars($columnas[0]['Field'] ?? 'id') ?> = <?= htmlspecialchars($registro[$columnas[0]['Field']] ?? 'Nuevo', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>)
  </h6>

  <form method="POST" action="index.php?controller=GestionController&action=actualizarRegistroEdit">
    <input type="hidden" name="db" value="<?= htmlspecialchars($db ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>">
    <input type="hidden" name="table" value="<?= htmlspecialchars($table ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>">
    <input type="hidden" name="id" value="<?= htmlspecialchars($registro[$columnas[0]['Field']] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>">

    <?php foreach ($columnas as $col): 
        $nombre = $col['Field'];
        if (strtolower($nombre) === 'id') continue; // omitimos id si existe
        $valor = $registro[$nombre] ?? ''; // valor seguro
    ?>
      <div class="mb-3">
        <label class="form-label"><?= htmlspecialchars($nombre, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></label>
        <input type="text" 
               name="<?= htmlspecialchars($nombre, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" 
               value="<?= htmlspecialchars($valor, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" 
               class="form-control" />
      </div>
    <?php endforeach; ?>

    <div class="d-flex gap-2 mt-4">
      <button type="submit" class="btn btn-success">
        <i class="bi bi-check-circle"></i> Guardar cambios
      </button>
      <a href="index.php?controller=GestionController&action=ver&db=<?= urlencode($db ?? '') ?>&table=<?= urlencode($table ?? '') ?>" 
         class="btn btn-secondary">
        <i class="bi bi-x-circle"></i> Cancelar
      </a>
    </div>
  </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>

<style>
  .container {
    max-width: 700px;
  }
  .form-label {
    font-weight: 500;
  }
  input.form-control {
    border-radius: 8px;
  }
  button.btn-success {
    min-width: 140px;
  }
</style>
