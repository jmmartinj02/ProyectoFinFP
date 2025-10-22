<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= htmlspecialchars($title ?? 'Gestor Remoto BD') ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

<style>
  body {
    background-color: #f7f9fc;
    font-family: 'Segoe UI', sans-serif;
  }
  .navbar {
    background: linear-gradient(90deg, #3b6ef5, #2648c0);
    padding: 0.8rem 1rem;
  }
  .navbar-brand {
    font-weight: 600;
    color: #fff !important;
    letter-spacing: 0.5px;
  }
  .nav-item {
    margin-right: 1rem;
  }
  .nav-link {
    color: #f0f0f0 !important;
    font-weight: 500;
    padding: 0.4rem 0.8rem;
    border-radius: 6px;
    transition: background-color 0.2s ease;
  }
  .nav-link:hover, .nav-link.active {
    background-color: rgba(255, 255, 255, 0.15);
    color: #fff !important;
  }
  .navbar .btn-outline-light {
    font-size: 0.9rem;
    padding: 0.35rem 0.9rem;
  }
  .header-spacer {
    height: 1rem;
  }
</style>
</head>
<body>

<!-- NAV PRINCIPAL -->
<nav class="navbar navbar-expand">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">
      <i class="bi bi-hdd-network"></i> GestorRemotoBD
    </a>
    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
      <li class="nav-item">
        <a class="nav-link<?= (($_GET['action'] ?? '') === 'dashboard') ? ' active' : '' ?>" 
           href="index.php?controller=GestionController&action=dashboard">
          <i class="bi bi-speedometer2"></i> Dashboard
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link<?= (($_GET['action'] ?? '') === 'inicio') ? ' active' : '' ?>" 
           href="index.php?controller=GestionController&action=inicio">
          <i class="bi bi-database"></i> Bases de datos
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link<?= (($_GET['action'] ?? '') === 'consultas') ? ' active' : '' ?>" 
           href="index.php?controller=GestionController&action=consultas">
          <i class="bi bi-terminal"></i> Consultas SQL
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link<?= (($_GET['action'] ?? '') === 'crearBD') ? ' active' : '' ?>" 
           href="index.php?controller=GestionController&action=crearBD">
          <i class="bi bi-plus-circle"></i> Crear BD
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link<?= (($_GET['action'] ?? '') === 'crearTabla') ? ' active' : '' ?>" 
           href="index.php?controller=GestionController&action=crearTabla">
          <i class="bi bi-grid-1x2"></i> Crear tabla
        </a>
      </li>
    </ul>
    <a href="index.php?controller=LoginController&action=logout" class="btn btn-outline-light">
      <i class="bi bi-box-arrow-right"></i> Salir
    </a>
  </div>
</nav>

<div class="header-spacer"></div>
