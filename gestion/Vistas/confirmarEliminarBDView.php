<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="container mt-5 mb-5">
  <div class="card shadow-sm border-0">
    <div class="card-body">
      <h4 class="text-danger mb-3"><i class="bi bi-exclamation-triangle"></i> Confirmar eliminación</h4>
      <p>Estás a punto de eliminar la base de datos:</p>
      <h5 class="text-primary"><?= htmlspecialchars($db) ?></h5>

      <div class="alert alert-warning mt-3">
        <i class="bi bi-info-circle"></i>
        Esta acción eliminará <strong>todas las tablas y datos</strong> dentro de esta base de datos de forma permanente.  
        No se podrá recuperar la información una vez completada.
      </div>

      <form method="post" action="index.php?controller=GestionController&action=eliminarBD">
        <input type="hidden" name="db" value="<?= htmlspecialchars($db) ?>">
        <div class="d-flex justify-content-start mt-4 gap-3">
          <button type="submit" class="btn btn-danger">
            <i class="bi bi-trash"></i> Confirmar eliminación
          </button>
          <a href="index.php?controller=GestionController&action=inicio" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Cancelar
          </a>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
