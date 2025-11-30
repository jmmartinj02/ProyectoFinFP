<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="container mt-4">

  <h2><i class="bi bi-folder2"></i> Copias disponibles</h2>

  <?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= $error ?></div>
  <?php endif; ?>

  <?php if (empty($backups)): ?>
    <p class="text-muted">No hay backups disponibles.</p>
  <?php else: ?>

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Archivo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>

        <?php foreach ($backups as $b): ?>
            <tr>
                <td><?= htmlspecialchars($b) ?></td>
                <td>
                    <a class="btn btn-sm btn-success" 
                       href="index.php?controller=BackupController&action=descargarBackup&file=<?= urlencode($b) ?>">
                       <i class="bi bi-download"></i> Descargar
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>

        </tbody>
    </table>

  <?php endif; ?>

  <a class="btn btn-secondary mt-3" href="index.php?controller=BackupController&action=inicio">
    Volver
  </a>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
