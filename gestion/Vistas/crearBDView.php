<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="container mt-4 mb-5">
  <h3><i class="bi bi-plus-circle"></i> Crear nueva base de datos</h3>
  <p class="text-muted">Introduce el nombre de la nueva base de datos que deseas crear.</p>

  <?php if (!empty($exito)): ?>
    <div class="alert alert-success">
      <i class="bi bi-check-circle"></i> <?= $exito ?>
    </div>
  <?php elseif (!empty($error)): ?>
    <div class="alert alert-danger">
      <i class="bi bi-exclamation-triangle"></i> <?= $error ?>
    </div>
  <?php endif; ?>

  <form method="post" action="index.php?controller=GestionController&action=procesarCrearBD" class="mt-3">
    <div class="mb-3">
      <label for="nombreBD" class="form-label">Nombre de la base de datos</label>
      <input type="text" class="form-control" id="nombreBD" name="db" placeholder="ejemplo_bd" required>
    </div>
    <button type="submit" class="btn btn-primary"><i class="bi bi-check2-circle"></i> Crear</button>
  </form>

  <div class="mt-4">
    <a href="index.php?controller=GestionController&action=dashboard" class="btn btn-secondary">
      <i class="bi bi-arrow-left"></i> Volver
    </a>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
