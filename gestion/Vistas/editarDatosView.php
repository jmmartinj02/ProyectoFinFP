<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="container mt-4 mb-5">
  <h3><i class="bi bi-pencil"></i> Editar datos en tabla</h3>
  <p class="text-muted">Base de datos: <strong><?= htmlspecialchars($bd) ?></strong> | Tabla: <strong><?= htmlspecialchars($tabla) ?></strong></p>

  <div class="alert alert-info">
    <i class="bi bi-info-circle"></i> Aquí se podrán ver y modificar registros específicos de la tabla.
  </div>

  <div class="mt-4">
    <a href="index.php?controller=GestionController&action=verTablas&bd=<?= urlencode($bd) ?>" class="btn btn-secondary">
      <i class="bi bi-arrow-left"></i> Volver
    </a>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
