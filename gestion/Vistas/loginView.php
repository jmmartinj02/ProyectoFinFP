<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Gestor Remoto BD - Acceso</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    background: linear-gradient(120deg, #f0f4ff, #e8f0ff);
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    font-family: 'Segoe UI', sans-serif;
}
.card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    background-color: #ffffff;
}
.btn-primary {
    background-color: #3b6ef5;
    border: none;
    transition: 0.3s;
}
.btn-primary:hover {
    background-color: #2a5de5;
}
h4 {
    font-weight: 600;
    color: #2a3a6b;
}
label {
    font-weight: 500;
}
</style>
</head>
<body>
<div class="card p-4" style="width: 360px;">
  <div class="text-center mb-3">
    <img src="https://cdn-icons-png.flaticon.com/512/4329/4329445.png" width="60" alt="logo">
  </div>
  <h4 class="text-center mb-4">Acceso al Gestor</h4>
  <?php if (!empty($error)): ?>
    <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>
  <form method="post" action="index.php?controller=LoginController&action=autenticar">
    <div class="mb-3">
      <label class="form-label">Host</label>
      <input type="text" class="form-control" name="host" placeholder="localhost o IP" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Usuario</label>
      <input type="text" class="form-control" name="user" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Contraseña</label>
      <input type="password" class="form-control" name="pass">
    </div>
    <div class="mb-3">
      <label class="form-label">Base de datos</label>
      <input type="text" class="form-control" name="db" placeholder="(opcional)">
    </div>
    <button class="btn btn-primary w-100 mt-2">Conectar</button>
  </form>
</div>
</body>
</html>
