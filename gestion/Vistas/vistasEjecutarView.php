<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="container mt-4">
    <h2>
        <i class="bi bi-play-circle"></i>
        Ejecutar Vista: <?= htmlspecialchars($vista['nombre']) ?>
    </h2>

    <p class="text-muted">Base: <?= htmlspecialchars($vista['db']) ?></p>

    <?php if (is_string($resultado)): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($resultado) ?>
        </div>
    <?php else: ?>

        <?php if (empty($resultado)): ?>
            <div class="alert alert-info">La consulta no devolvió resultados.</div>

        <?php else: ?>
            <div class="table-responsive mt-3">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <?php foreach (array_keys($resultado[0]) as $col): ?>
                                <th><?= htmlspecialchars($col) ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($resultado as $fila): ?>
                        <tr>
                            <?php foreach ($fila as $v): ?>
                                <td><?= htmlspecialchars($v) ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    <?php endif; ?>

</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
