<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="container mt-5 mb-5">
  <h2 class="mb-3"><i class="bi bi-journal-text"></i> Configuración del sistema de logs</h2>
  <p class="text-muted">
    No se ha detectado una base de datos destinada a los registros de actividad (logs).
    Se recomienda crear una base separada para registrar todas las operaciones realizadas
    por el sistema gestor.
  </p>

  <form method="POST" action="index.php?controller=LogsController&action=crearBaseLogs" class="mt-4">
    <div class="mb-3">
      <label for="nombreLogs" class="form-label fw-semibold">Nombre de la base de datos de logs:</label>
      <input type="text" class="form-control" id="nombreLogs" name="nombreLogs"
             placeholder="Ejemplo: gestor_logs" required>
    </div>

    <button type="submit" class="btn btn-primary">
      <i class="bi bi-plus-circle"></i> Crear base de datos
    </button>
    <a href="index.php?controller=GestionController&action=inicio" class="btn btn-secondary">
      Cancelar
    </a>
  </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
