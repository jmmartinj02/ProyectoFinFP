<?php include __DIR__ . '/includes/header.php'; ?>

<div class="container mt-5">

    <div class="alert alert-warning">
        <h4><i class="bi bi-arrow-counterclockwise"></i> Confirmar restauración</h4>
        <p>Estás a punto de restaurar esta copia de seguridad:</p>
        <p><strong><?= htmlspecialchars($archivo) ?></strong></p>

        <p class="mt-3 text-danger">
            <strong>⚠ Esta acción reemplazará los datos actuales de la base de datos.</strong><br>
            Por favor, asegúrate antes de continuar.
        </p>
    </div>

    <a href="index.php?controller=BackupController&action=restaurar&file=<?= urlencode($archivo) ?>"
       class="btn btn-warning">
        <i class="bi bi-arrow-counterclockwise"></i> Restaurar ahora
    </a>

    <a href="index.php?controller=BackupController&action=index"
       class="btn btn-secondary">
       Cancelar
    </a>

</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
