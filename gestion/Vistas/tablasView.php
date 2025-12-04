<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="container mt-4 mb-5">

  <!-- Botón volver -->
  <a href="index.php?controller=GestionController&action=inicio" 
     class="btn btn-outline-primary mb-3">
    <i class="bi bi-arrow-left"></i> Volver
  </a>

  <!-- Título y botón contextual -->
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">
      <i class="bi bi-database"></i>
      <?php if (!empty($registros)): ?>
        Registros en <?= htmlspecialchars($table) ?>
      <?php elseif (!empty($table) && empty($registros)): ?>
        Tabla <?= htmlspecialchars($table) ?> (vacía)
      <?php else: ?>
        Tablas en <?= htmlspecialchars($dbName) ?>
      <?php endif; ?>
    </h3>

    <!-- BOTONES CONTEXTUALES CORREGIDOS -->
    <?php if (empty($table)): ?>
      <!-- Estamos viendo la lista de tablas (con o sin tablas) -->
      <a href="index.php?controller=GestionController&action=formCrearTabla&db=<?= urlencode($dbName) ?>" 
         class="btn btn-success btn-sm">
        <i class="bi bi-plus-circle"></i> Crear nueva tabla
      </a>
    <?php elseif (!empty($table) && !empty($registros)): ?>
      <!-- Estamos viendo registros (hay registros) -->
      <a href="index.php?controller=GestionController&action=formInsertarRegistro&db=<?= urlencode($dbName) ?>&table=<?= urlencode($table) ?>" 
         class="btn btn-success btn-sm">
        <i class="bi bi-plus-circle"></i> Insertar nuevo registro
      </a>
    <?php elseif (!empty($table) && empty($registros)): ?>
      <!-- Estamos viendo una tabla vacía (no hay registros) -->
      <a href="index.php?controller=GestionController&action=formInsertarRegistro&db=<?= urlencode($dbName) ?>&table=<?= urlencode($table) ?>" 
         class="btn btn-success btn-sm">
        <i class="bi bi-plus-circle"></i> Insertar primer registro
      </a>
    <?php endif; ?>
  </div>

  <p class="text-muted mb-4">
    <?php if (empty($table)): ?>
      Selecciona una tabla para ver su contenido o gestiona su estructura.
    <?php elseif (!empty($registros)): ?>
      Mostrando <?= count($registros) ?> registro(s) en esta tabla.
    <?php else: ?>
      Esta tabla está vacía. Puedes insertar el primer registro.
    <?php endif; ?>
  </p>

  <!-- Listado de tablas (solo se muestra cuando NO estamos viendo registros específicos) -->
  <?php if (empty($table) && !empty($tablas)): ?>
    <div class="list-group mb-4 shadow-sm rounded">
      <?php foreach ($tablas as $t): ?>
        <div class="list-group-item d-flex justify-content-between align-items-center py-3">
          <div class="d-flex align-items-center gap-2">
            <i class="bi bi-table text-primary"></i>
            <a href="index.php?controller=GestionController&action=ver&db=<?= urlencode($dbName) ?>&table=<?= urlencode($t) ?>" 
               class="fw-semibold text-decoration-none text-dark">
              <?= htmlspecialchars($t) ?>
            </a>
          </div>
          <div class="d-flex align-items-center gap-3">
            <a href="index.php?controller=GestionController&action=confirmarEliminarTabla&db=<?= urlencode($dbName) ?>&table=<?= urlencode($t) ?>" 
               class="btn btn-outline-danger btn-sm px-2 py-1">
              <i class="bi bi-trash"></i>
            </a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <!-- Mensaje cuando no hay tablas -->
  <?php if (empty($table) && empty($tablas)): ?>
    <div class="alert alert-warning">
      <i class="bi bi-exclamation-triangle"></i>
      No se encontraron tablas en esta base de datos.
    </div>
  <?php endif; ?>

  <!-- Vista de registros -->
  <?php if (!empty($registros)): ?>
    <h4 class="mt-5 mb-3">
      <i class="bi bi-list-ul"></i> Registros en <strong><?= htmlspecialchars($table) ?></strong>
    </h4>

    <div class="table-responsive">
      <table class="table table-bordered table-sm align-middle">
        <thead class="table-light">
          <tr>
            <?php foreach (array_keys($registros[0]) as $col): ?>
              <th><?= htmlspecialchars($col) ?></th>
            <?php endforeach; ?>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($registros as $fila): ?>
            <tr>
              <?php foreach ($fila as $valor): ?>
                <td><?= htmlspecialchars($valor) ?></td>
              <?php endforeach; ?>

              <td class="text-center">
                <?php
                  // Detectar clave (id o primera columna disponible)
                  $claveColumna = array_key_first($fila);
                  $claveValor = $fila[$claveColumna] ?? null;
                ?>

                <?php if ($claveValor !== null): ?>
                  <div class="d-flex justify-content-center gap-2">
                    <!-- Botón Editar -->
                    <a href="index.php?controller=GestionController&action=editarRegistroEdit&db=<?= urlencode($dbName) ?>&table=<?= urlencode($table) ?>&id=<?= urlencode($claveValor) ?>" 
                      class="btn btn-sm btn-outline-primary">
                      <i class="bi bi-pencil"></i> Editar
                    </a>

                    <!-- Botón Eliminar -->
                    <a href="index.php?controller=GestionController&action=eliminarRegistro&db=<?= urlencode($dbName) ?>&table=<?= urlencode($table) ?>&id=<?= urlencode($claveValor) ?>" 
                      class="btn btn-sm btn-outline-danger">
                      <i class="bi bi-trash"></i> Eliminar
                    </a>
                  </div>
                <?php else: ?>
                  <span class="text-muted small">Sin clave</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php elseif (!empty($table) && empty($registros)): ?>
    <!-- Mensaje para tabla vacía -->
    <div class="alert alert-info mt-4">
      <i class="bi bi-info-circle"></i>
      La tabla <strong><?= htmlspecialchars($table) ?></strong> no contiene registros.
    </div>
  <?php endif; ?>

</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>

<style>
  .list-group-item {
    transition: background-color 0.2s ease, transform 0.1s ease;
  }
  .list-group-item:hover {
    background-color: #f5f8ff;
    transform: scale(1.01);
  }
  .btn-outline-danger.btn-sm {
    border-radius: 8px;
  }
</style>