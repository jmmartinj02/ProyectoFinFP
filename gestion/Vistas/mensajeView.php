<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="container mt-5 mb-5">
  <div class="card shadow-sm border-0">
    <div class="card-body text-center p-4">
      <h4 class="mb-4"><i class="bi bi-exclamation-triangle text-danger"></i> Confirmar acción</h4>
      <p class="fs-5 mb-4"><?= htmlspecialchars($mensaje ?? '¿Deseas continuar con esta acción?') ?></p>

      <div class="d-flex justify-content-center gap-3">
        <?php if (!empty($confirmar)): ?>
          <a href="<?= htmlspecialchars($confirmar) ?>" class="btn btn-danger px-4">
            <i class="bi bi-trash"></i> Confirmar
          </a>
        <?php endif; ?>

        <?php if (!empty($volver)): ?>
          <a href="<?= htmlspecialchars($volver) ?>" class="btn btn-secondary px-4">
            <i class="bi bi-arrow-left"></i> Cancelar
          </a>
        <?php else: ?>
          <a href="index.php?controller=GestionController&action=inicio" class="btn btn-secondary px-4">
            <i class="bi bi-arrow-left"></i> Volver al inicio
          </a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
