<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="container mt-4">
  <h2><i class="bi bi-eye"></i> Crear nueva vista SQL</h2>

  <?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="POST"
        action="index.php?controller=GestionController&action=crearVista">

        <!-- IMPORTANTE: enviar la base de datos elegida -->
        <input type="hidden" name="db" value="<?= htmlspecialchars($db) ?>">

        <div class="mb-3">
        <label class="form-label fw-semibold">Nombre de la vista</label>
        <input type="text" class="form-control" name="nombre"
                placeholder="Ej: usuarios_activos" required>
        </div>

        <div class="mb-3">
        <label class="form-label fw-semibold">Consulta SQL</label>
        <textarea class="form-control" name="consulta" rows="10"
                    placeholder="Ej: SELECT * FROM usuarios WHERE activo = 1" required></textarea>
        </div>

        <button type="submit">Crear</button>
        </button>

        <a class="btn btn-secondary"
            href="index.php?controller=GestionController&action=vistas&db=<?= urlencode($db) ?>">
            Cancelar
        </a>
    </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
