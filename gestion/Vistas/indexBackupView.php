<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="container mt-4">

    <h2><i class="bi bi-hdd-stack"></i> Copias de seguridad</h2>
    <p class="text-muted">Aquí puedes crear, descargar, restaurar o eliminar copias de seguridad de la base de datos.</p>

    <div class="mb-3">
        <a href="index.php?controller=BackupController&action=crear" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Crear copia nueva
        </a>
    </div>

    <?php if (empty($backups)): ?>
        <div class="alert alert-info mt-3">
            <i class="bi bi-info-circle"></i> No hay copias de seguridad disponibles.
        </div>
    <?php else: ?>

        <table class="table table-striped mt-3">
            <thead class="table-dark">
                <tr>
                    <th>Archivo</th>
                    <th>Fecha</th>
                    <th>Tamaño</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($backups as $backup): ?>
                <tr>
                    <td><?= htmlspecialchars($backup['archivo']) ?></td>
                    <td><?= htmlspecialchars($backup['fecha']) ?></td>
                    <td><?= htmlspecialchars($backup['tamano']) ?></td>
                    <td>
                        <a class="btn btn-primary btn-sm"
                           href="index.php?controller=BackupController&action=descargar&file=<?= urlencode($backup['archivo']) ?>">
                            <i class="bi bi-download"></i>
                        </a>

                        <a class="btn btn-warning btn-sm"
                           href="index.php?controller=BackupController&action=confirmarRestaurar&file=<?= urlencode($backup['archivo']) ?>">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>

                        <a class="btn btn-danger btn-sm"
                           href="index.php?controller=BackupController&action=confirmarEliminar&file=<?= urlencode($backup['archivo']) ?>">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

    <?php endif; ?>

</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
