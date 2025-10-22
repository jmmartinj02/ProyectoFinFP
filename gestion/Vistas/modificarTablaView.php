<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="container mt-4 mb-5">
  <h3><i class="bi bi-pencil-square"></i> Modificar tabla</h3>
  <p class="text-muted">Editando estructura de: <strong><?= htmlspecialchars($tabla) ?></strong> en <strong><?= htmlspecialchars($bd) ?></strong></p>

  <p>En esta vista podrás añadir, eliminar o modificar columnas más adelante.</p>

  <div class="alert alert-info">
    <i class="bi bi-info-circle"></i> Funcionalidad en desarrollo.
  </div>

  <div class="mt-4">
    <a href="index.php?controller=GestionController&action=verTablas&bd=<?= urlencode($bd) ?>" class="btn btn-secondary">
      <i class="bi bi-arrow-left"></i> Volver
    </a>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
