<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="container mt-4 mb-5">
  <a href="index.php?controller=GestionController&action=tablas&db=<?= urlencode($db) ?>" 
     class="btn btn-outline-primary mb-3">
    <i class="bi bi-arrow-left"></i> Volver
  </a>

  <h3 class="mb-4"><i class="bi bi-table"></i> Crear nueva tabla en <?= htmlspecialchars($db) ?></h3>

  <form method="post" action="index.php?controller=GestionController&action=crearTabla">
    <input type="hidden" name="db" value="<?= htmlspecialchars($db) ?>">

    <div class="mb-3">
      <label class="form-label fw-semibold">Nombre de la tabla</label>
      <input type="text" name="nombre" class="form-control" placeholder="Ejemplo: usuarios" required>
    </div>

    <div class="mb-3">
      <label class="form-label fw-semibold">Columnas</label>
      <div class="border rounded p-3 bg-light">
        <?php for ($i = 1; $i <= 4; $i++): ?>
          <div class="row g-2 mb-2">
            <div class="col-md-6">
              <input type="text" name="columnas[<?= $i ?>][nombre]" class="form-control" placeholder="nombre_columna">
            </div>
            <div class="col-md-6">
              <input type="text" name="columnas[<?= $i ?>][tipo]" class="form-control" placeholder="Ej: VARCHAR(255), INT, DATE...">
            </div>
          </div>
        <?php endfor; ?>
      </div>
      <div class="form-text">Podemos definir hasta 4 columnas básicas. He de indicar un botón para las claves y en otro momento, si hay foraneas.</div>
    </div>

    <div class="mt-4">
      <button type="submit" class="btn btn-success">
        <i class="bi bi-check-circle"></i> Crear tabla
      </button>
      <a href="index.php?controller=GestionController&action=tablas&db=<?= urlencode($db) ?>" 
         class="btn btn-secondary">
        <i class="bi bi-x-circle"></i> Cancelar
      </a>
    </div>
  </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
