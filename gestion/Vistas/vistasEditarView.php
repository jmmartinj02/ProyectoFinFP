<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="container mt-4">
    <h2><i class="bi bi-pencil-square"></i> Editar Vista</h2>

    <form method="POST" class="mt-3">

        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control"
                   value="<?= htmlspecialchars($vista['nombre']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control"><?= htmlspecialchars($vista['descripcion']) ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Base de datos</label>
            <select name="db" class="form-select" required>
                <?php foreach ($bases as $b): ?>
                    <option value="<?= $b ?>" <?= $b === $vista['db'] ? 'selected' : '' ?>>
                        <?= $b ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Sentencia SQL</label>
            <textarea name="sql" class="form-control" rows="6" required><?= htmlspecialchars($vista['sql_text']) ?></textarea>
        </div>

        <button class="btn btn-success">Guardar cambios</button>

    </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
