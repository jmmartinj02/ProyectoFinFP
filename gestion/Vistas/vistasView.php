<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="container mt-4">
  <h2><i class="bi bi-window-stack"></i> Vistas de la base de datos <strong><?= htmlspecialchars($db) ?></strong></h2>
  
  <a href="index.php?controller=GestionController&action=formCrearVista&db=<?= urlencode($db) ?>"
    class="btn btn-success mt-3">
    <i class="bi bi-plus-circle"></i> Crear nueva vista
  </a>


  <?php if (empty($vistas)): ?>
    <p class="text-muted">No existen vistas en esta base de datos.</p>
  <?php else: ?>
    <table class="table table-hover">
      <thead>
        <tr>
          <th>Nombre de la vista</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($vistas as $vista): ?>
          <tr>
            <td><?= htmlspecialchars($vista) ?></td>
            <td>
            <a href="index.php?controller=GestionController&action=verVista&db=<?= urlencode($db) ?>&view=<?= urlencode($vista) ?>"
              class="btn btn-outline-primary btn-sm">
              <i class="bi bi-search"></i> Ver
            </a>
            <a href="index.php?controller=GestionController&action=eliminarVista&db=<?= urlencode($db) ?>&view=<?= urlencode($vista) ?>"
              class="btn btn-outline-danger btn-sm">
              <i class="bi bi-trash"></i> Eliminar
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
