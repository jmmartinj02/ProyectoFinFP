<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="container mt-4">
  <h2><i class="bi bi-folder2-open"></i> Copias de seguridad disponibles</h2>

  <?php if (empty($files['full']) && empty($files['incremental']) && empty($files['diferencial'])): ?>
      <div class="alert alert-warning">No hay copias disponibles.</div>
  <?php endif; ?>

  <?php foreach ($files as $tipo => $lista): ?>
      <h4 class="mt-3 text-capitalize"><?= $tipo ?></h4>
      <ul class="list-group">
      <?php foreach ($lista as $file): ?>
          <li class="list-group-item d-flex justify-content-between">
              <?= basename($file) ?>
              <div>
                    <a class="btn btn-sm btn-success"
                    href="index.php?controller=BackupController&action=descargar&tipo=<?= $tipo ?>&file=<?= basename($file) ?>">
                    Descargar
                    </a>

                    <a class="btn btn-sm btn-danger"
                    href="index.php?controller=BackupController&action=eliminar&tipo=<?= $tipo ?>&file=<?= basename($file) ?>"
                    onclick="return confirm('¿Seguro que deseas eliminar este backup?');">
                    Eliminar
                    </a>

              </div>
          </li>
      <?php endforeach; ?>
      </ul>
  <?php endforeach; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
