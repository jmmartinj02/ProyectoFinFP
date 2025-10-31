
<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="container mt-5 mb-5">
  <div class="card shadow-sm border-0">
    <div class="card-body text-center py-5">
      <i class="bi bi-exclamation-triangle text-danger" style="font-size: 3rem;"></i>
      <h3 class="mt-3 mb-3 text-danger">Confirmar eliminación</h3>

      <p>Vas a eliminar la tabla:</p>
      <h5 class="text-primary"><?= htmlspecialchars($tabla) ?></h5>
      <p class="text-muted mb-4">
        Esta acción eliminará todos los datos de forma permanente en la base de datos 
        <strong><?= htmlspecialchars($db) ?></strong>.
      </p>

      <form method="post" action="index.php?controller=GestionController&action=eliminarTabla">
        <input type="hidden" name="db" value="<?= htmlspecialchars($db) ?>">
        <input type="hidden" name="tabla" value="<?= htmlspecialchars($tabla) ?>">

        <div class="d-flex justify-content-center gap-3 mt-4">
          <button type="submit" class="btn btn-danger">
            <i class="bi bi-trash"></i> Eliminar tabla
          </button>
          <a href="index.php?controller=GestionController&action=tablas&db=<?= urlencode($db) ?>" 
             class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Cancelar
          </a>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
