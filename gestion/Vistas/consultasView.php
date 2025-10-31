<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="container mt-4 mb-5">
  <h3 class="mb-3"><i class="bi bi-terminal"></i> Consola de consultas SQL</h3>

  <?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="post" action="index.php?controller=GestionController&action=ejecutarConsulta">

    <!-- Selector de base de datos -->
    <div class="mb-3">
      <label class="form-label fw-semibold">Seleccionar base de datos:</label>
      <div class="d-flex flex-wrap gap-3">
        <?php if (!empty($bases)): ?>
          <?php foreach ($bases as $b): ?>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="base" id="base_<?= htmlspecialchars($b) ?>"
                     value="<?= htmlspecialchars($b) ?>"
                     <?= ($dbActual === $b) ? 'checked' : '' ?>>
              <label class="form-check-label" for="base_<?= htmlspecialchars($b) ?>">
                <?= htmlspecialchars($b) ?>
              </label>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p class="text-muted">No se encontraron bases de datos disponibles.</p>
        <?php endif; ?>
      </div>
    </div>

    <!-- Área de consulta -->
    <div class="mb-3">
      <label class="form-label fw-semibold">Consulta SQL:</label>
      <textarea name="sql" rows="6" class="form-control" placeholder="Escribe aquí tu consulta SQL..."><?= htmlspecialchars($sql ?? '') ?></textarea>
    </div>

    <div class="d-flex justify-content-between mb-3">
      <div>
        <label class="form-label">Límite de filas</label>
        <input type="number" name="limit" value="<?= htmlspecialchars($limit ?? 100) ?>" class="form-control d-inline-block" style="width:120px;">
      </div>
      <button class="btn btn-primary mt-4"><i class="bi bi-play-fill"></i> Ejecutar</button>
    </div>
  </form>

  <!-- Mostrar resultados -->
  <?php if (!empty($type) && $type === 'select' && !empty($data)): ?>
    <div class="mt-4">
      <h5>Resultados (<?= count($data) ?> filas)</h5>
      <div class="table-responsive">
        <table class="table table-sm table-bordered">
          <thead class="table-light">
            <tr>
              <?php foreach (array_keys($data[0]) as $col): ?>
                <th><?= htmlspecialchars($col) ?></th>
              <?php endforeach; ?>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($data as $row): ?>
              <tr>
                <?php foreach ($row as $val): ?>
                  <td><?= htmlspecialchars((string)$val) ?></td>
                <?php endforeach; ?>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  <?php elseif (!empty($type) && $type === 'action'): ?>
    <div class="alert alert-success mt-3">
      Consulta ejecutada correctamente. Filas afectadas: <?= htmlspecialchars((string)$affected ?? 0) ?>.
    </div>
  <?php elseif (!empty($error)): ?>
    <div class="alert alert-danger mt-3"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
