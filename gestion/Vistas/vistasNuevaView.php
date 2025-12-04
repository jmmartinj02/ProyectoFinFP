<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="container mt-4">
    <h2><i class="bi bi-plus-circle"></i> Crear nueva Vista SQL</h2>

    <form method="POST" class="mt-3">

        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Base de datos</label>
            <select name="db" class="form-select" required>
                <option value="">-- Seleccionar --</option>
                <?php foreach ($bases as $b): ?>
                    <option value="<?= $b ?>"><?= $b ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Sentencia SQL</label>
            <textarea name="sql" class="form-control" rows="6" required></textarea>
        </div>

        <button class="btn btn-success">Guardar Vista</button>

    </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
