<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="container mt-4 mb-5">
  <h2><i class="bi bi-plus-circle"></i> Crear nueva vista en <strong><?= htmlspecialchars($db) ?></strong></h2>

  <form method="POST" action="index.php?controller=GestionController&action=crearVista">
    <input type="hidden" name="db" value="<?= htmlspecialchars($db) ?>">

    <div class="mb-3">
      <label class="form-label">Nombre de la vista:</label>
      <input type="text" name="nombre" class="form-control" placeholder="ejemplo_vista" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Consulta SQL (SELECT):</label>
      <textarea name="consulta" class="form-control" rows="6" placeholder="SELECT columna FROM tabla WHERE ..." required></textarea>
    </div>

    <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Crear vista</button>
    <a href="index.php?controller=GestionController&action=vistas&db=<?= urlencode($db) ?>" class="btn btn-secondary">Cancelar</a>
  </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
