<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="container mt-4">
    <h2><i class="bi bi-collection"></i> Vistas SQL guardadas</h2>

    <a href="index.php?controller=VistasController&action=nueva"
       class="btn btn-primary mt-3">
       <i class="bi bi-plus-circle"></i> Nueva vista
    </a>

    <table class="table table-striped mt-4">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Base de datos</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($vistas as $v): ?>
                <tr>
                    <td><?= htmlspecialchars($v['nombre']) ?></td>
                    <td><?= htmlspecialchars($v['descripcion']) ?></td>
                    <td><?= htmlspecialchars($v['db']) ?></td>
                    <td><?= $v['fecha'] ?></td>
                    <td>
                        <a class="btn btn-sm btn-success"
                        href="index.php?controller=VistasController&action=ejecutar&id=<?= $v['id'] ?>">
                        Ejecutar
                        </a>

                        <a class="btn btn-sm btn-warning"
                        href="index.php?controller=VistasController&action=editar&id=<?= $v['id'] ?>">
                        Editar
                        </a>

                        <a class="btn btn-sm btn-danger"
                        href="index.php?controller=VistasController&action=eliminar&id=<?= $v['id'] ?>"
                        onclick="return confirm('¿Eliminar esta vista?');">
                        Eliminar
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>

    </table>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
