<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="container mt-4">

    <h2><i class="bi bi-collection"></i> Vistas en la base de datos: 
        <strong><?= htmlspecialchars($db) ?></strong>
    </h2>

    <div class="mt-3 mb-4">
        <a href="index.php?controller=GestionController&action=crearVista&db=<?= urlencode($db) ?>" 
           class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nueva vista
        </a>

        <a href="index.php?controller=GestionController&action=inicio" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>

    <?php if (empty($vistas)): ?>

        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> No hay vistas creadas en esta base de datos.
        </div>

    <?php else: ?>

        <table class="table table-striped table-hover">
            <thead class="table-dark">
            <tr>
                <th>Nombre de la Vista</th>
                <th class="text-end">Acciones</th>
            </tr>
            </thead>
            <tbody>

            <?php foreach ($vistas as $vista): ?>
                <tr>
                    <td><?= htmlspecialchars($vista) ?></td>
                    <td class="text-end">
                        <a href="index.php?controller=GestionController&action=verVista&db=<?= urlencode($db) ?>&vista=<?= urlencode($vista) ?>"
                           class="btn btn-sm btn-info">
                            <i class="bi bi-eye"></i> Ver
                        </a>

                        <a href="index.php?controller=GestionController&action=eliminarVista&db=<?= urlencode($db) ?>&vista=<?= urlencode($vista) ?>"
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('¿Seguro que deseas eliminar esta vista?');">
                            <i class="bi bi-trash"></i> Eliminar
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>

            </tbody>
        </table>
    <?php endif; ?>

</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
