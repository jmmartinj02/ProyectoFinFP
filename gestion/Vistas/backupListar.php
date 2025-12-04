<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="container mt-4">

  <h2><i class="bi bi-folder2-open"></i> Copias disponibles</h2>

  <?php foreach ($files as $tipo => $lista): ?>
    
    <h4 class="mt-4 text-capitalize"><?= $tipo ?></h4>
    
    <!-- Mostrar aviso solo para Incremental y Diferencial -->
    <?php if ($tipo == "incremental" || $tipo == "diferencial"): ?>
      <p class="text-muted"><small><i class="bi bi-exclamation-triangle"></i> Nota: Esta función está en desarrollo, las copias realizadas contienen un no operacional.</small></p>
    <?php endif; ?>
    
    <ul class="list-group">

      <?php foreach ($lista as $path): ?>
        <?php $file = basename($path); ?>

        <li class="list-group-item d-flex justify-content-between">
          <span><?= $file ?></span>

          <div>
            <a class="btn btn-success btn-sm"
              href="index.php?controller=BackupController&action=descargar&file=<?= urlencode($file) ?>">
              Descargar
            </a>

            <a class="btn btn-danger btn-sm"
              href="index.php?controller=BackupController&action=eliminar&file=<?= urlencode($file) ?>"
              onclick="return confirm('¿Eliminar esta copia?')">
              Eliminar
            </a>
          </div>
        </li>
      <?php endforeach; ?>

    </ul>

  <?php endforeach; ?>

</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>