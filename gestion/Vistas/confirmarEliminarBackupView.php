<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="container mt-5">

    <div class="alert alert-danger">
        <h4><i class="bi bi-exclamation-triangle"></i> Confirmar eliminación</h4>
        <p>¿Estás seguro de que deseas eliminar la copia de seguridad:</p>
        <p><strong><?= htmlspecialchars($archivo) ?></strong></p>
    </div>

    <a href="index.php?controller=BackupController&action=eliminar&file=<?= urlencode($archivo) ?>"
       class="btn btn-danger">
        <i class="bi bi-trash"></i> Eliminar definitivamente
    </a>

    <a href="index.php?controller=BackupController&action=index"
       class="btn btn-secondary">
       Cancelar
    </a>

</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
