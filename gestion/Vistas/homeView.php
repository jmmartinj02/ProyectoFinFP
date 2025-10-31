<style>
  .list-group-item {
    transition: background-color 0.2s ease, transform 0.1s ease;
  }
  .list-group-item:hover {
    background-color: #f5f8ff;
    transform: scale(1.01);
  }
  .btn-outline-danger.btn-sm {
    border-radius: 8px;
  }
</style>

<?php include __DIR__ . '/../includes/header.php'; ?>

    <div class="container mt-4">
    <h2 class="mb-4">Panel de Gestión de Bases de Datos</h2>
    <p class="text-muted">Selecciona una base de datos para ver sus tablas.</p>

    <?php if (!empty($databases)): ?>
  <div class="list-group shadow-sm rounded">
    <?php foreach ($databases as $db): ?>
      <div class="list-group-item d-flex justify-content-between align-items-center py-3">
        <div class="d-flex align-items-center gap-2">
          <i class="bi bi-database text-primary fs-5"></i>
          <a href="index.php?controller=GestionController&action=tablas&db=<?= urlencode($db) ?>" 
             class="fw-semibold text-decoration-none text-dark">
            <?= htmlspecialchars($db) ?>
          </a>
        </div>
        <div class="d-flex align-items-center gap-3">
          <i class="bi bi-chevron-right text-muted"></i>
          <a href="index.php?controller=GestionController&action=confirmarEliminarBD&db=<?= urlencode($db) ?>" 
             class="btn btn-outline-danger btn-sm px-2 py-1">
            <i class="bi bi-trash"></i>
          </a>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php else: ?>
  <div class="alert alert-warning mt-3">
    <i class="bi bi-exclamation-triangle"></i>
    No se encontraron bases de datos o no tienes permisos para verlas.
  </div>
<?php endif; ?>


<?php include __DIR__ . '/../includes/footer.php'; ?>
