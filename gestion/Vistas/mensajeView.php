<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="container mt-5 mb-5">
  <div class="card shadow-sm border-0">
    <div class="card-body text-center py-5">
      <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
      <h3 class="mt-3 mb-3">Operación completada</h3>
      
      <p class="lead"><?= $mensaje ?? 'Acción realizada correctamente.' ?></p>
      
      <?php if (!empty($enlace)): ?>
        <a href="<?= htmlspecialchars($enlace) ?>" class="btn btn-primary mt-3">
          <i class="bi bi-arrow-left-circle"></i> Volver
        </a>
      <?php else: ?>
        <a href="index.php" class="btn btn-primary mt-3">
          <i class="bi bi-house-door"></i> Volver al inicio
        </a>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
