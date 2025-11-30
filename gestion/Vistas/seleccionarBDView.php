<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="container mt-5">
  <h2 class="mb-3"><i class="bi bi-database"></i> Selecciona una base de datos</h2>
  <p class="text-muted">
    Debes seleccionar una base de datos activa para poder gestionar sus vistas SQL.
  </p>

  <form method="GET" action="index.php">

    <!-- Destino del formulario -->
    <input type="hidden" name="controller" value="GestionController">
    <input type="hidden" name="action" value="vistas">

    <!-- AGREGADO: mantener la DB seleccionada en la sesión -->
    <?php if (!empty($_SESSION['current_db'])): ?>
      <input type="hidden" name="current_db" value="<?= htmlspecialchars($_SESSION['current_db']) ?>">
    <?php endif; ?>

    <div class="mb-3">
      <label for="db" class="form-label fw-semibold">Base de datos disponible:</label>

      <select class="form-select" id="db" name="db" required>
        <option value="">
          -- Selecciona una base --
        </option>

        <?php foreach ($bases as $base): ?>
          <option 
            value="<?= htmlspecialchars($base) ?>"
            <?= ($_SESSION['current_db'] ?? '') === $base ? 'selected' : '' ?>
          >
            <?= htmlspecialchars($base) ?>
          </option>
        <?php endforeach; ?>

      </select>
    </div>

    <button type="submit" class="btn btn-primary">
      <i class="bi bi-arrow-right-circle"></i> Continuar
    </button>

    <a href="index.php?controller=GestionController&action=crearBD" class="btn btn-success">
      <i class="bi bi-plus-circle"></i> Crear nueva base de datos
    </a>
  </form>

  <?php if (!empty($_SESSION['current_db'])): ?>
    <div class="alert alert-info mt-3">
      <i class="bi bi-info-circle"></i>
      Actualmente seleccionada: <strong><?= htmlspecialchars($_SESSION['current_db']) ?></strong>
    </div>
  <?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
