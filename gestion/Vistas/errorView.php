<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="container mt-5 text-center">
  <div class="alert alert-danger shadow-sm p-4">
    <h4><i class="bi bi-exclamation-triangle-fill"></i> Error</h4>
    <p class="mt-2"><?= htmlspecialchars($mensaje ?? 'Ha ocurrido un error desconocido.') ?></p>
    <a href="index.php" class="btn btn-outline-primary mt-3">
      <i class="bi bi-arrow-left"></i> Volver al inicio
    </a>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
